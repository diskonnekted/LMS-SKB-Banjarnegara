<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function store(Request $request, Quiz $quiz)
    {
        if (!Auth::user()->hasRole('admin') && $quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_answer' => 'required|in:a,b,c,d',
        ]);

        $options = [
            'a' => $request->option_a,
            'b' => $request->option_b,
            'c' => $request->option_c,
            'd' => $request->option_d,
        ];

        $quiz->questions()->create([
            'question' => $request->question,
            'options' => $options,
            'correct_answer' => $request->correct_answer,
        ]);

        return back()->with('success', 'Question added.');
    }

    public function destroy(Question $question)
    {
        if (!Auth::user()->hasRole('admin') && $question->quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $question->delete();
        return back()->with('success', 'Question deleted.');
    }
}
