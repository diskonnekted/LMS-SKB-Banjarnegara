<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function edit(Question $question)
    {
        if (! Auth::user()->hasRole('admin') && $question->quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $question->load('quiz');

        $options = is_array($question->options) ? $question->options : (json_decode((string) $question->options, true) ?? []);
        $correctAnswers = [];
        if ($question->type === 'multiple_response') {
            $correctAnswers = json_decode((string) $question->correct_answer, true) ?? [];
        }

        $pairs = [];
        if (in_array($question->type, ['matching', 'drag_drop'], true)) {
            $pairs = is_array($options) ? array_values($options) : [];
        }

        $sequenceItems = [];
        if ($question->type === 'sequencing') {
            $sequenceItems = json_decode((string) $question->correct_answer, true) ?? [];
        }

        return view('questions.edit', compact('question', 'options', 'correctAnswers', 'pairs', 'sequenceItems'));
    }

    public function update(Request $request, Question $question)
    {
        if (! Auth::user()->hasRole('admin') && $question->quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'question' => 'required|string',
            'type' => 'required|string',
        ]);

        $options = [];
        $correct_answer = '';

        switch ($request->type) {
            case 'multiple_choice':
                $request->validate([
                    'option_a' => 'required|string',
                    'option_b' => 'required|string',
                    'option_c' => 'required|string',
                    'option_d' => 'required|string',
                    'option_e' => 'required|string',
                    'correct_answer' => 'required|in:a,b,c,d,e',
                ]);
                $options = [
                    'a' => $request->option_a,
                    'b' => $request->option_b,
                    'c' => $request->option_c,
                    'd' => $request->option_d,
                    'e' => $request->option_e,
                ];
                $correct_answer = $request->correct_answer;
                break;

            case 'multiple_response':
                $request->validate([
                    'option_a' => 'required|string',
                    'option_b' => 'required|string',
                    'option_c' => 'required|string',
                    'option_d' => 'required|string',
                    'option_e' => 'required|string',
                    'correct_answers' => 'required|array',
                    'correct_answers.*' => 'in:a,b,c,d,e|distinct',
                ]);
                $options = [
                    'a' => $request->option_a,
                    'b' => $request->option_b,
                    'c' => $request->option_c,
                    'd' => $request->option_d,
                    'e' => $request->option_e,
                ];
                $correct_answer = json_encode($request->correct_answers);
                break;

            case 'true_false':
                $request->validate([
                    'correct_answer' => 'required|in:true,false',
                ]);
                $options = ['true' => 'True', 'false' => 'False'];
                $correct_answer = $request->correct_answer;
                break;

            case 'short_answer':
            case 'numeric':
                $request->validate([
                    'correct_answer_text' => 'required|string',
                ]);
                $correct_answer = $request->correct_answer_text;
                break;

            case 'essay':
                break;

            case 'matching':
            case 'drag_drop':
                $request->validate([
                    'pairs' => 'required|array|min:2',
                    'pairs.*.left' => 'required|string',
                    'pairs.*.right' => 'required|string',
                ]);
                $options = array_values($request->pairs);
                $correct_answer = 'matching';
                break;

            case 'sequencing':
                $request->validate([
                    'sequence' => 'required|array|min:2',
                    'sequence.*' => 'required|string',
                ]);
                $correct_answer = json_encode(array_values($request->sequence));
                $options = array_values($request->sequence);
                break;
        }

        if (! in_array($request->type, ['matching', 'drag_drop', 'sequencing'], true) && ! empty($options)) {
            $options = array_filter($options, function ($value) {
                return ! is_null($value) && $value !== '';
            });
        }

        $question->update([
            'question' => $request->question,
            'type' => $request->type,
            'options' => $options,
            'correct_answer' => $correct_answer,
            'media_url' => $request->media_url,
        ]);

        return redirect()->route('quizzes.edit', $question->quiz)->with('success', 'Question updated.');
    }

    public function store(Request $request, Quiz $quiz)
    {
        if (! Auth::user()->hasRole('admin') && $quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'question' => 'required|string',
            'type' => 'required|string',
        ]);

        $options = [];
        $correct_answer = '';

        switch ($request->type) {
            case 'multiple_choice':
                $request->validate([
                    'option_a' => 'required|string',
                    'option_b' => 'required|string',
                    'option_c' => 'required|string',
                    'option_d' => 'required|string',
                    'option_e' => 'required|string',
                    'correct_answer' => 'required|in:a,b,c,d,e',
                ]);
                $options = [
                    'a' => $request->option_a,
                    'b' => $request->option_b,
                    'c' => $request->option_c,
                    'd' => $request->option_d,
                    'e' => $request->option_e,
                ];
                $correct_answer = $request->correct_answer;
                break;

            case 'multiple_response':
                $request->validate([
                    'option_a' => 'required|string',
                    'option_b' => 'required|string',
                    'option_c' => 'required|string',
                    'option_d' => 'required|string',
                    'option_e' => 'required|string',
                    'correct_answers' => 'required|array',
                    'correct_answers.*' => 'in:a,b,c,d,e|distinct',
                ]);
                $options = [
                    'a' => $request->option_a,
                    'b' => $request->option_b,
                    'c' => $request->option_c,
                    'd' => $request->option_d,
                    'e' => $request->option_e,
                ];
                $correct_answer = json_encode($request->correct_answers);
                break;

            case 'true_false':
                $request->validate([
                    'correct_answer' => 'required|in:true,false',
                ]);
                $options = ['true' => 'True', 'false' => 'False'];
                $correct_answer = $request->correct_answer;
                break;

            case 'short_answer':
            case 'numeric':
                $request->validate([
                    'correct_answer_text' => 'required|string',
                ]);
                $correct_answer = $request->correct_answer_text;
                break;

            case 'essay':
                break;

            case 'matching':
            case 'drag_drop':
                $request->validate([
                    'pairs' => 'required|array|min:2',
                    'pairs.*.left' => 'required|string',
                    'pairs.*.right' => 'required|string',
                ]);
                $options = array_values($request->pairs);
                $correct_answer = 'matching';
                break;

            case 'sequencing':
                $request->validate([
                    'sequence' => 'required|array|min:2',
                    'sequence.*' => 'required|string',
                ]);
                $correct_answer = json_encode(array_values($request->sequence));
                // Store items in options too, so we know what the items are (even if we shuffle them later)
                $options = array_values($request->sequence);
                break;
        }

        // Clean up empty options if it's an array AND not one of the new types where structure matters
        if (! in_array($request->type, ['matching', 'drag_drop', 'sequencing']) && ! empty($options)) {
            $options = array_filter($options, function ($value) {
                return ! is_null($value) && $value !== '';
            });
        }

        $quiz->questions()->create([
            'question' => $request->question,
            'type' => $request->type,
            'options' => $options,
            'correct_answer' => $correct_answer,
            'media_url' => $request->media_url,
        ]);

        return back()->with('success', 'Question added.');
    }

    public function destroy(Question $question)
    {
        if (! Auth::user()->hasRole('admin') && $question->quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $question->delete();

        return back()->with('success', 'Question deleted.');
    }
}
