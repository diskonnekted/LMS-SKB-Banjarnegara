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
            'type' => 'required|string',
        ]);

        $options = [];
        $correct_answer = '';

        switch ($request->type) {
            case 'multiple_choice':
                $request->validate([
                    'option_a' => 'required|string',
                    'option_b' => 'required|string',
                    'correct_answer' => 'required|in:a,b,c,d',
                ]);
                $options = [
                    'a' => $request->option_a,
                    'b' => $request->option_b,
                    'c' => $request->option_c,
                    'd' => $request->option_d,
                ];
                $correct_answer = $request->correct_answer;
                break;

            case 'multiple_response':
                 $request->validate([
                    'option_a' => 'required|string',
                    'option_b' => 'required|string',
                    'correct_answers' => 'required|array',
                ]);
                $options = [
                    'a' => $request->option_a,
                    'b' => $request->option_b,
                    'c' => $request->option_c,
                    'd' => $request->option_d,
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
        if (!in_array($request->type, ['matching', 'drag_drop', 'sequencing']) && !empty($options)) {
             $options = array_filter($options, function($value) {
                return !is_null($value) && $value !== '';
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
        if (!Auth::user()->hasRole('admin') && $question->quiz->lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $question->delete();
        return back()->with('success', 'Question deleted.');
    }
}
