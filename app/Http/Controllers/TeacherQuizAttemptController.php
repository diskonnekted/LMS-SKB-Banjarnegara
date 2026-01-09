<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
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
