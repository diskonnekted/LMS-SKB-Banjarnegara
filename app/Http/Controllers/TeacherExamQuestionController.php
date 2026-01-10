<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class TeacherExamQuestionController extends Controller
{
    public function store(Request $request, Exam $exam)
    {
        $this->authorizeOwner($exam);

        $request->validate([
            'type' => 'required|in:multiple_choice,multiple_response,true_false,short_answer,numeric',
            'question' => 'required|string',
            'points' => 'required|integer|min:1|max:1000',
            'order' => 'nullable|integer|min:0|max:100000',
            'media_file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf',
        ]);

        [$options, $correctAnswer] = $this->buildOptionsAndCorrect($request);

        $mediaPath = null;
        if ($request->hasFile('media_file')) {
            $mediaPath = $request->file('media_file')->store('exam-questions', 'public');
        }

        $exam->questions()->create([
            'type' => $request->type,
            'question' => $request->question,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'media_url' => $mediaPath,
            'points' => $request->points,
            'order' => (int) ($request->order ?? 0),
        ]);

        return redirect()->route('teacher.exams.edit', $exam)->with('success', 'Soal ujian ditambahkan.');
    }

    public function edit(ExamQuestion $examQuestion)
    {
        $examQuestion->load('exam');
        $this->authorizeOwner($examQuestion->exam);

        $options = is_array($examQuestion->options) ? $examQuestion->options : (json_decode((string) $examQuestion->options, true) ?? []);
        $correctAnswers = [];
        if ($examQuestion->type === 'multiple_response') {
            $correctAnswers = json_decode((string) $examQuestion->correct_answer, true) ?? [];
        }

        return view('teacher.exams.questions.edit', [
            'question' => $examQuestion,
            'options' => $options,
            'correctAnswers' => $correctAnswers,
        ]);
    }

    public function update(Request $request, ExamQuestion $examQuestion)
    {
        $examQuestion->load('exam');
        $this->authorizeOwner($examQuestion->exam);

        $request->validate([
            'type' => 'required|in:multiple_choice,multiple_response,true_false,short_answer,numeric',
            'question' => 'required|string',
            'points' => 'required|integer|min:1|max:1000',
            'order' => 'nullable|integer|min:0|max:100000',
            'media_file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf',
            'remove_media' => 'nullable|boolean',
        ]);

        [$options, $correctAnswer] = $this->buildOptionsAndCorrect($request);

        $mediaPath = $examQuestion->media_url;
        if ($request->hasFile('media_file')) {
            if (is_string($mediaPath) && $mediaPath !== '' && ! Str::startsWith($mediaPath, ['http://', 'https://'])) {
                Storage::disk('public')->delete($mediaPath);
            }
            $mediaPath = $request->file('media_file')->store('exam-questions', 'public');
        } elseif ($request->boolean('remove_media')) {
            if (is_string($mediaPath) && $mediaPath !== '' && ! Str::startsWith($mediaPath, ['http://', 'https://'])) {
                Storage::disk('public')->delete($mediaPath);
            }
            $mediaPath = null;
        }

        $examQuestion->update([
            'type' => $request->type,
            'question' => $request->question,
            'options' => $options,
            'correct_answer' => $correctAnswer,
            'media_url' => $mediaPath,
            'points' => $request->points,
            'order' => (int) ($request->order ?? 0),
        ]);

        return redirect()->route('teacher.exams.edit', $examQuestion->exam)->with('success', 'Soal ujian diperbarui.');
    }

    public function destroy(ExamQuestion $examQuestion)
    {
        $examQuestion->load('exam');
        $this->authorizeOwner($examQuestion->exam);

        $exam = $examQuestion->exam;
        $examQuestion->delete();

        return redirect()->route('teacher.exams.edit', $exam)->with('success', 'Soal ujian dihapus.');
    }

    private function buildOptionsAndCorrect(Request $request): array
    {
        $type = $request->type;
        $options = null;
        $correctAnswer = null;

        if ($type === 'multiple_choice') {
            $request->validate([
                'option_a' => 'required|string',
                'option_b' => 'required|string',
                'option_c' => 'nullable|string',
                'option_d' => 'nullable|string',
                'option_e' => 'nullable|string',
            ]);

            $pickedCorrectAnswer = $request->input('correct_answer_mc', $request->input('correct_answer'));
            if ($pickedCorrectAnswer === null || $pickedCorrectAnswer === '') {
                throw ValidationException::withMessages([
                    'correct_answer_mc' => 'Kunci jawaban wajib diisi.',
                ]);
            }
            if (! in_array($pickedCorrectAnswer, ['a', 'b', 'c', 'd', 'e'], true)) {
                throw ValidationException::withMessages([
                    'correct_answer_mc' => 'Kunci jawaban tidak valid.',
                ]);
            }

            $options = array_filter([
                'a' => $request->option_a,
                'b' => $request->option_b,
                'c' => $request->option_c,
                'd' => $request->option_d,
                'e' => $request->option_e,
            ], fn ($v) => $v !== null && $v !== '');

            if (! array_key_exists($pickedCorrectAnswer, $options)) {
                throw ValidationException::withMessages([
                    'correct_answer_mc' => 'Kunci jawaban harus sesuai opsi yang diisi.',
                ]);
            }

            $correctAnswer = $pickedCorrectAnswer;
        }

        if ($type === 'multiple_response') {
            $request->validate([
                'option_a' => 'required|string',
                'option_b' => 'required|string',
                'option_c' => 'nullable|string',
                'option_d' => 'nullable|string',
                'option_e' => 'nullable|string',
                'correct_answers' => 'required|array',
                'correct_answers.*' => 'in:a,b,c,d,e|distinct',
            ]);
            $options = array_filter([
                'a' => $request->option_a,
                'b' => $request->option_b,
                'c' => $request->option_c,
                'd' => $request->option_d,
                'e' => $request->option_e,
            ], fn ($v) => $v !== null && $v !== '');

            $picked = $request->correct_answers;
            $missing = array_filter($picked, fn ($k) => ! array_key_exists($k, $options));
            if (! empty($missing)) {
                throw ValidationException::withMessages([
                    'correct_answers' => 'Kunci jawaban harus sesuai opsi yang diisi.',
                ]);
            }
            $correctAnswer = json_encode(array_values($picked));
        }

        if ($type === 'true_false') {
            $pickedCorrectAnswer = $request->input('correct_answer_tf', $request->input('correct_answer'));
            if ($pickedCorrectAnswer === null || $pickedCorrectAnswer === '') {
                throw ValidationException::withMessages([
                    'correct_answer_tf' => 'Kunci jawaban wajib diisi.',
                ]);
            }
            if (! in_array($pickedCorrectAnswer, ['true', 'false'], true)) {
                throw ValidationException::withMessages([
                    'correct_answer_tf' => 'Kunci jawaban tidak valid.',
                ]);
            }
            $options = ['true' => 'True', 'false' => 'False'];
            $correctAnswer = $pickedCorrectAnswer;
        }

        if (in_array($type, ['short_answer', 'numeric'], true)) {
            $request->validate([
                'correct_answer_text' => 'required|string',
            ]);
            $options = null;
            $correctAnswer = $request->correct_answer_text;
        }

        return [$options, $correctAnswer];
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
