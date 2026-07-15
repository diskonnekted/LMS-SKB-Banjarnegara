<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeacherQuizAttemptController extends Controller
{
    public function index(Quiz $quiz)
    {
        $this->ensureCanViewQuiz($quiz);

        $quiz->load(['lesson.module.course']);

        $attempts = QuizAttempt::query()
            ->where('quiz_id', $quiz->id)
            ->with('user')
            ->latest()
            ->paginate(20);

        return view('teacher.quizzes.attempts.index', compact('quiz', 'attempts'));
    }

    public function show(QuizAttempt $attempt)
    {
        $attempt->load(['user', 'quiz.questions', 'quiz.lesson.module.course']);

        $this->ensureCanViewQuiz($attempt->quiz);

        $answers = QuizAttemptAnswer::query()
            ->where('quiz_attempt_id', $attempt->id)
            ->get()
            ->keyBy('question_id');

        return view('teacher.quizzes.attempts.show', compact('attempt', 'answers'));
    }

    public function gradeAnswer(Request $request, QuizAttemptAnswer $answer)
    {
        $answer->load(['attempt.quiz.questions', 'attempt.quiz.lesson.module.course', 'question']);

        $this->ensureCanViewQuiz($answer->attempt->quiz);

        if (($answer->question->type ?? null) !== 'essay') {
            abort(404);
        }

        $data = $request->validate([
            'score' => 'required|integer|min:0|max:100',
        ]);

        $score = (int) $data['score'];

        $answer->update([
            'score' => $score,
            'is_correct' => $score >= 70,
        ]);

        $attempt = $answer->attempt;
        $attempt->load(['quiz.questions', 'answers']);

        $essayQuestionIds = $attempt->quiz->questions
            ->where('type', 'essay')
            ->pluck('id')
            ->values();

        if ($essayQuestionIds->isNotEmpty()) {
            $pendingEssays = $attempt->answers
                ->whereIn('question_id', $essayQuestionIds)
                ->filter(fn ($row) => is_null($row->score))
                ->count();

            if ($pendingEssays === 0) {
                $total = $attempt->quiz->questions->count();
                $totalPoints = 0;
                foreach ($attempt->quiz->questions as $q) {
                    $ans = $attempt->answers->where('question_id', $q->id)->first();
                    if ($ans) {
                        if ($q->type === 'essay') {
                            $totalPoints += (int) ($ans->score ?? 0);
                        } else {
                            $totalPoints += $ans->is_correct ? 100 : 0;
                        }
                    }
                }
                $newScore = $total > 0 ? (int) round($totalPoints / $total) : 0;

                $attempt->update([
                    'score' => $newScore,
                    'passed' => $newScore >= (int) $attempt->quiz->passing_score,
                ]);
            }
        }

        return back()->with('success', 'Penilaian esai disimpan.');
    }

    private function ensureCanViewQuiz(Quiz $quiz): void
    {
        $user = Auth::user();
        if ($user->hasRole('admin')) {
            return;
        }

        if ($quiz->lesson->module->course->teacher_id !== $user->id) {
            abort(403);
        }
    }
}
