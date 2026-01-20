<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class QuizController extends Controller
{
    public function create(Lesson $lesson)
    {
        if (! Auth::user()->hasRole('admin') && $lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        // Check if quiz already exists for this lesson
        if ($lesson->quiz) {
            return redirect()->route('quizzes.edit', $lesson->quiz);
        }

        return view('quizzes.create', compact('lesson'));
    }

    public function store(Request $request, Lesson $lesson)
    {
        if (! Auth::user()->hasRole('admin') && $lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $quiz = $lesson->quiz()->create([
            'title' => $request->title,
            'passing_score' => $request->passing_score,
        ]);

        return redirect()->route('quizzes.edit', $quiz)->with('success', 'Quiz created. Now add questions.');
    }

    public function edit(Quiz $quiz)
    {
        if (! Auth::user()->hasRole('admin') && $quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $quiz->load(['questions', 'lesson.module.course']);

        $baseUrl = request()->getBaseUrl();
        $studentLink = url($baseUrl.route('learning.quiz', [$quiz->lesson->module->course, $quiz->lesson->module, $quiz], false));

        $qrBase64 = null;
        try {
            $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data='.urlencode($studentLink);
            $response = Http::timeout(5)->get($qrUrl);
            if ($response->successful()) {
                $qrBase64 = base64_encode($response->body());
            }
        } catch (\Throwable $e) {
        }

        return view('quizzes.edit', compact('quiz', 'baseUrl', 'studentLink', 'qrBase64'));
    }

    public function downloadQr(Quiz $quiz)
    {
        if (! Auth::user()->hasRole('admin') && $quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $quiz->loadMissing(['lesson.module.course']);

        $baseUrl = request()->getBaseUrl();
        $studentLink = url($baseUrl.route('learning.quiz', [$quiz->lesson->module->course, $quiz->lesson->module, $quiz], false));

        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=512x512&data='.urlencode($studentLink);
        $response = Http::timeout(10)->get($qrUrl);
        if (! $response->successful()) {
            abort(503, 'QR tidak tersedia.');
        }

        $filename = 'qr-kuis-'.Str::slug((string) $quiz->title).'-'.$quiz->id.'.png';

        return response($response->body(), 200)
            ->header('Content-Type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }

    public function update(Request $request, Quiz $quiz)
    {
        if (! Auth::user()->hasRole('admin') && $quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $quiz->update($request->only('title', 'passing_score'));

        return back()->with('success', 'Quiz updated.');
    }

    public function destroy(Quiz $quiz)
    {
        if (! Auth::user()->hasRole('admin') && $quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $course = $quiz->lesson->module->course;
        $quiz->delete();

        return redirect()->route('courses.modules.index', $course)->with('success', 'Quiz deleted.');
    }
}
