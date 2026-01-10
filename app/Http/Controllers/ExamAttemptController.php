<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ExamAttemptController extends Controller
{
    public function take(string $code)
    {
        $exam = Exam::query()->where('access_code', $code)->firstOrFail();

        $user = Auth::user();
        $canPreview = $user->hasRole('admin') || (int) $exam->teacher_id === (int) $user->id;
        if (! $canPreview) {
            if (! $exam->is_published) {
                abort(404);
            }
            if (! $exam->isOpenNow()) {
                abort(403);
            }
        }

        $questions = $exam->questions()->orderBy('order')->orderBy('id')->get();

        return view('exams.take', compact('exam', 'questions'));
    }

    public function submit(Request $request, string $code)
    {
        $exam = Exam::query()->where('access_code', $code)->firstOrFail();
        $user = Auth::user();
        if (! $user->hasRole('student')) {
            abort(403);
        }
        if (! $exam->is_published || ! $exam->isOpenNow()) {
            abort(403);
        }

        $questions = $exam->questions()->orderBy('order')->orderBy('id')->get();
        $totalPoints = (int) $questions->sum('points');
        $earnedPoints = 0;
        $answerRows = [];
        $now = now();

        foreach ($questions as $question) {
            $submitted = $request->input('q_'.$question->id);
            $isCorrect = null;
            $earned = 0;

            switch ($question->type) {
                case 'multiple_choice':
                case 'true_false':
                    $isCorrect = $submitted !== null && (string) $submitted === (string) $question->correct_answer;
                    break;

                case 'multiple_response':
                    $corrects = json_decode((string) $question->correct_answer, true) ?? [];
                    if (is_array($submitted)) {
                        sort($submitted);
                        sort($corrects);
                        $isCorrect = $submitted === $corrects;
                    } else {
                        $isCorrect = false;
                    }
                    break;

                case 'short_answer':
                case 'numeric':
                    if ($submitted === null) {
                        $isCorrect = false;
                    } else {
                        $isCorrect = strcasecmp(trim((string) $submitted), trim((string) $question->correct_answer)) === 0;
                    }
                    break;
            }

            if ($isCorrect === true) {
                $earned = (int) $question->points;
                $earnedPoints += $earned;
            }

            $storedAnswer = null;
            if (is_array($submitted)) {
                $storedAnswer = json_encode($submitted);
            } elseif ($submitted !== null) {
                $storedAnswer = (string) $submitted;
            }

            $answerRows[] = [
                'exam_question_id' => $question->id,
                'answer' => $storedAnswer,
                'is_correct' => $isCorrect,
                'earned_points' => $earned,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $score = $totalPoints > 0 ? (int) round(($earnedPoints / $totalPoints) * 100) : 0;
        $passed = $score >= (int) $exam->passing_score;

        $attempt = \DB::transaction(function () use ($user, $exam, $earnedPoints, $totalPoints, $score, $passed, $answerRows, $now) {
            $attempt = ExamAttempt::create([
                'user_id' => $user->id,
                'exam_id' => $exam->id,
                'earned_points' => $earnedPoints,
                'total_points' => $totalPoints,
                'score' => $score,
                'passed' => $passed,
                'started_at' => $now,
                'submitted_at' => $now,
            ]);

            if (! empty($answerRows)) {
                $rows = array_map(function (array $row) use ($attempt) {
                    $row['exam_attempt_id'] = $attempt->id;

                    return $row;
                }, $answerRows);
                \DB::table('exam_attempt_answers')->insert($rows);
            }

            return $attempt;
        });

        return redirect()->route('exams.result', $attempt);
    }

    public function result(ExamAttempt $attempt)
    {
        $attempt->load(['exam', 'answers.question']);

        $user = Auth::user();
        $canView = (int) $attempt->user_id === (int) $user->id;
        if (! $canView) {
            $canView = $user->hasRole('admin') || (int) $attempt->exam->teacher_id === (int) $user->id;
        }
        if (! $canView) {
            abort(403);
        }

        return view('exams.result', [
            'attempt' => $attempt,
            'exam' => $attempt->exam,
        ]);
    }

    public function downloadPdf(ExamAttempt $attempt)
    {
        $attempt->load(['exam.course', 'exam.teacher', 'user']);

        $user = Auth::user();
        $canView = (int) $attempt->user_id === (int) $user->id;
        if (! $canView) {
            $canView = $user->hasRole('admin') || (int) $attempt->exam->teacher_id === (int) $user->id;
        }
        if (! $canView) {
            abort(403);
        }

        $exam = $attempt->exam;
        $student = $attempt->user;

        $title = (string) $exam->title;
        $courseTitle = $exam->course ? (string) $exam->course->title : '-';
        $gradeLevel = $exam->grade_level ?: '-';
        $teacherName = $exam->teacher ? (string) $exam->teacher->name : '-';
        $submittedAt = $attempt->submitted_at ? $attempt->submitted_at->format('d/m/Y H:i') : '-';

        $statusLabel = $attempt->passed ? 'LULUS' : 'TIDAK LULUS';
        $statusColor = $attempt->passed ? '#16a34a' : '#dc2626';

        $html = '<!doctype html><html><head><meta charset="utf-8"><style>'
            .'body{font-family:DejaVu Sans, sans-serif;font-size:12px;color:#111827;}'
            .'.h1{font-size:18px;font-weight:700;margin:0 0 6px 0;}'
            .'.muted{color:#6b7280;}'
            .'.box{border:1px solid #e5e7eb;border-radius:10px;padding:14px;margin:0 0 14px 0;}'
            .'.row{width:100%;border-collapse:collapse;}'
            .'.row td{vertical-align:top;padding:6px 0;}'
            .'.label{width:160px;color:#6b7280;}'
            .'.score{font-size:22px;font-weight:800;}'
            .'.status{display:inline-block;padding:6px 10px;border-radius:999px;color:#fff;font-weight:700;}'
            .'</style></head><body>'
            .'<div class="box">'
            .'<div class="h1">Hasil Ujian</div>'
            .'<div class="muted">Dokumen ini dihasilkan otomatis oleh sistem.</div>'
            .'</div>'
            .'<div class="box">'
            .'<table class="row">'
            .'<tr><td class="label">Nama Ujian</td><td>'.e($title).'</td></tr>'
            .'<tr><td class="label">Mata Pelajaran</td><td>'.e($courseTitle).'</td></tr>'
            .'<tr><td class="label">Kelas</td><td>'.e($gradeLevel).'</td></tr>'
            .'<tr><td class="label">Guru</td><td>'.e($teacherName).'</td></tr>'
            .'<tr><td class="label">Tanggal Ujian</td><td>'.e($submittedAt).'</td></tr>'
            .'<tr><td class="label">Kode Ujian</td><td>'.e((string) $exam->access_code).'</td></tr>'
            .'</table>'
            .'</div>'
            .'<div class="box">'
            .'<table class="row">'
            .'<tr><td class="label">Nama Siswa</td><td>'.e((string) $student->name).'</td></tr>'
            .'<tr><td class="label">Email</td><td>'.e((string) $student->email).'</td></tr>'
            .'</table>'
            .'</div>'
            .'<div class="box">'
            .'<table class="row">'
            .'<tr><td class="label">Nilai</td><td><span class="score">'.e((string) $attempt->score).'%</span></td></tr>'
            .'<tr><td class="label">Poin</td><td>'.e((string) $attempt->earned_points).' / '.e((string) $attempt->total_points).'</td></tr>'
            .'<tr><td class="label">Status</td><td><span class="status" style="background:'.$statusColor.'">'.e($statusLabel).'</span></td></tr>'
            .'<tr><td class="label">KKM</td><td>'.e((string) ((int) $exam->passing_score)).'%</td></tr>'
            .'</table>'
            .'</div>'
            .'</body></html>';

        Pdf::setOptions(['isRemoteEnabled' => false]);

        $filename = 'hasil-ujian-'.Str::slug((string) $exam->title).'-'.$attempt->id.'.pdf';
        $pdf = Pdf::loadHTML($html)->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }
}
