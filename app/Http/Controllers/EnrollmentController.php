<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Course $course)
    {
        $user = Auth::user();
        if (! $user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            $user->enrolledCourses()->attach($course->id);

            return redirect()->route('learning.course', $course)->with('success', 'Enrolled successfully!');
        }

        return redirect()->route('learning.course', $course);
    }

    public function destroy(Course $course, \App\Models\User $user)
    {
        $currentUser = Auth::user();
        if (! $currentUser->hasRole('admin') && $course->teacher_id !== $currentUser->id) {
            abort(403);
        }

        // Detach course enrollment
        $course->students()->detach($user->id);

        // Delete lesson progress
        $lessonIds = $course->modules->flatMap->lessons->pluck('id');
        if ($lessonIds->isNotEmpty()) {
            \DB::table('lesson_user')
                ->where('user_id', $user->id)
                ->whereIn('lesson_id', $lessonIds)
                ->delete();
        }

        return redirect()->back()->with('success', 'Siswa berhasil dihapus dari pelajaran ini.');
    }
}
