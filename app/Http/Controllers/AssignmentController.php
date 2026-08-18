<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    public function index(Module $module, Lesson $lesson)
    {
        $assignments = $lesson->assignments()
            ->when(request('search'), function ($query) {
                return $query->where('title', 'like', '%' . request('search') . '%');
            })
            ->orderBy('due_date')
            ->get();

        return view('assignments.index', compact('module', 'lesson', 'assignments'));
    }

    public function create(Module $module, Lesson $lesson)
    {
        return view('assignments.create', compact('module', 'lesson'));
    }

    public function store(Request $request, Module $module, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'file_size_limit' => 'nullable|integer|min:1|max:50',
        ]);

        $lesson->assignments()->create($validated + ['is_active' => true]);

        return redirect()->route('modules.lessons.assignments.index', [$module, $lesson])
            ->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function edit(Module $module, Lesson $lesson, Assignment $assignment)
    {
        return view('assignments.edit', compact('module', 'lesson', 'assignment'));
    }

    public function update(Request $request, Module $module, Lesson $lesson, Assignment $assignment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:today',
            'file_size_limit' => 'nullable|integer|min:1|max:50',
            'is_active' => 'boolean',
        ]);

        $assignment->update($validated);

        return redirect()->route('modules.lessons.assignments.index', [$module, $lesson])
            ->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy(Module $module, Lesson $lesson, Assignment $assignment)
    {
        $assignment->delete();

        return redirect()->route('modules.lessons.assignments.index', [$module, $lesson])
            ->with('success', 'Tugas berhasil dihapus.');
    }
}
