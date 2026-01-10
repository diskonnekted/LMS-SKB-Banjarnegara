<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Illuminate\Support\Facades\Auth;

class TeacherExamAttemptController extends Controller
{
    public function index(Exam $exam)
    {
        $this->authorizeOwner($exam);

        $attempts = ExamAttempt::query()
            ->where('exam_id', $exam->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('teacher.exams.attempts.index', compact('exam', 'attempts'));
    }

    public function show(ExamAttempt $attempt)
    {
        $attempt->load(['exam', 'user', 'answers.question']);
        $this->authorizeOwner($attempt->exam);

        return view('teacher.exams.attempts.show', [
            'attempt' => $attempt,
            'exam' => $attempt->exam,
        ]);
    }

    private function authorizeOwner(Exam $exam): void
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return;
        }
        if ((int) $exam->teacher_id !== (int) $user->id) {
            abort(403);
        }
    }
}
