<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    /**
     * Student: View and submit assignment
     */
    public function show(Course $course, Module $module, Lesson $lesson, Assignment $assignment)
    {
        $user = Auth::user();
        $submission = $assignment->submissions()
            ->where('user_id', $user->id)
            ->first();

        return view('submissions.show', compact('course', 'module', 'lesson', 'assignment', 'submission'));
    }

    /**
     * Student: Submit/upload assignment
     */
    public function store(Request $request, Course $course, Module $module, Lesson $lesson, Assignment $assignment)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'file' => 'required|file|max:'.$assignment->file_size_limit*1024,
            'notes' => 'nullable|string|max:1000',
        ]);

        // Check if already submitted
        $existing = $assignment->submissions()->where('user_id', $user->id)->first();

        if ($existing) {
            // Delete old file if exists and it's not a re-upload of the same file
            if ($existing->file_path && $request->file('file')) {
                Storage::disk('public')->delete($existing->file_path);
            }
            // Reset grading if re-submitting
            $existing->update([
                'file_path' => $request->file('file')->store('submissions', 'public'),
                'notes' => $validated['notes'],
                'status' => 'pending',
                'score' => null,
                'feedback' => null,
                'graded_at' => null,
            ]);

            return back()->with('success', 'Tugas berhasil diperbarui.');
        }

        $submission = $assignment->submissions()->create([
            'user_id' => $user->id,
            'file_path' => $request->file('file')->store('submissions', 'public'),
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Tugas berhasil dikirim.');
    }

    /**
     * Teacher: View submissions for an assignment
     */
    public function index(Module $module, Lesson $lesson, Assignment $assignment)
    {
        $students = $module->course->students()->get();
        $submissions = $assignment->submissions()
            ->with(['user', 'gradedBy'])
            ->orderBy('status')
            ->orderBy('created_at', 'desc')
            ->get()
            ->keyBy('user_id');

        return view('submissions.index', compact('module', 'lesson', 'assignment', 'students', 'submissions'));
    }

    /**
     * Teacher: Grade a submission
     */
    public function grade(Request $request, Submission $submission)
    {
        $validated = $request->validate([
            'score' => 'nullable|numeric|min:0|max:100',
            'feedback' => 'nullable|string|max:2000',
        ]);

        $submission->update([
            'status' => 'graded',
            'score' => $validated['score'] ?? null,
            'feedback' => $validated['feedback'] ?? null,
            'graded_by' => auth()->id(),
            'graded_at' => now(),
        ]);

        return back()->with('success', 'Penilaian berhasil disimpan.');
    }
}
