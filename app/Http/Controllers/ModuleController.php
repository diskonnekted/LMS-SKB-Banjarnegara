<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function index(Course $course)
    {
        if (! Auth::user()->hasRole('admin') && $course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $course->load(['modules.lessons']);

        return view('modules.index', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        if (! Auth::user()->hasRole('admin') && $course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $course->modules()->create([
            'title' => $request->title,
            'order' => $course->modules()->count() + 1,
        ]);

        return redirect()->route('courses.modules.index', $course)->with('success', 'Module added successfully.');
    }

    public function update(Request $request, Module $module)
    {
        if (! Auth::user()->hasRole('admin') && $module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $module->update($request->only('title'));

        return back()->with('success', 'Module updated.');
    }

    public function destroy(Module $module)
    {
        if (! Auth::user()->hasRole('admin') && $module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $module->delete();

        return back()->with('success', 'Module deleted.');
    }
}
