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
            'is_correct' => 'required|boolean',
        ]);

        $answer->update([
            'is_correct' => (bool) $data['is_correct'],
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
                ->filter(fn ($row) => is_null($row->is_correct))
                ->count();

            if ($pendingEssays === 0) {
                $total = $attempt->quiz->questions->count();
                $correct = $attempt->answers->where('is_correct', true)->count();
                $newScore = $total > 0 ? (int) round(($correct / $total) * 100) : 0;

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
