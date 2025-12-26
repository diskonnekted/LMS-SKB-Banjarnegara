<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Course $course)
    {
        $user = Auth::user();
        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            $user->enrolledCourses()->attach($course->id);
            return redirect()->route('learning.course', $course)->with('success', 'Enrolled successfully!');
        }
        
        return redirect()->route('learning.course', $course);
    }
}
