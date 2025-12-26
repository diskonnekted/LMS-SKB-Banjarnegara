<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index(Course $course)
    {
        $user = Auth::user();
        
        // Ensure user is enrolled
        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course);
        }

        // Check completion status
        $totalLessons = $course->modules->flatMap->lessons->count();
        $completedLessons = $user->completedLessons()
            ->whereIn('lesson_id', $course->modules->flatMap->lessons->pluck('id'))
            ->count();

        if ($totalLessons > 0 && $completedLessons >= $totalLessons) {
            return view('learning.completed', compact('course'));
        }

        // Get the first lesson of the first module to redirect to
        $firstModule = $course->modules()->orderBy('order')->first();
        
        if (!$firstModule) {
             return redirect()->route('courses.show', $course)->with('error', 'This course has no content yet.');
        }
        
        $firstLesson = $firstModule->lessons()->orderBy('order')->first();
        
        if (!$firstLesson) {
             return redirect()->route('courses.show', $course)->with('error', 'This course has no content yet.');
        }

        // Ideally redirect to last accessed lesson, but for now first is fine or first incomplete
        return redirect()->route('learning.lesson', [$course, $firstModule, $firstLesson]);
    }

    public function show(Course $course, Module $module, Lesson $lesson)
    {
        $user = Auth::user();

        // 1. Authorization: Enrolled?
        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
             return redirect()->route('courses.show', $course);
        }

        // 2. Sequential Access Logic
        if (!$this->canAccessModule($user, $course, $module)) {
            return redirect()->back()->with('error', 'You must complete previous modules first.');
        }

        // Load navigation data
        $course->load(['modules.lessons.usersCompleted' => function ($query) use ($user) {
            $query->where('user_id', $user->id);
        }]);

        // Check if current lesson is completed
        $isCompleted = $user->completedLessons()->where('lesson_id', $lesson->id)->exists();

        return view('learning.show', compact('course', 'module', 'lesson', 'isCompleted'));
    }

    public function complete(Course $course, Module $module, Lesson $lesson)
    {
        $user = Auth::user();
        if (!$user->completedLessons()->where('lesson_id', $lesson->id)->exists()) {
            $user->completedLessons()->attach($lesson->id, ['completed' => true]);
        }

        // Find next lesson
        $nextLesson = $module->lessons()->where('order', '>', $lesson->order)->orderBy('order')->first();
        
        if ($nextLesson) {
            return redirect()->route('learning.lesson', [$course, $module, $nextLesson]);
        }

        // If no next lesson in module, check for quiz
        if ($lesson->quiz) {
             return redirect()->route('learning.quiz', [$course, $module, $lesson->quiz]);
        }
        
        // If no quiz, check next module
        $nextModule = $course->modules()->where('order', '>', $module->order)->orderBy('order')->first();
        
        if ($nextModule) {
            $nextModuleLesson = $nextModule->lessons()->orderBy('order')->first();
            if ($nextModuleLesson) {
                 return redirect()->route('learning.lesson', [$course, $nextModule, $nextModuleLesson]);
            }
        }

        return redirect()->route('learning.course', $course)->with('success', 'Course Completed!');
    }

    public function quiz(Course $course, Module $module, Quiz $quiz)
    {
         $user = Auth::user();
         // Check access
         if (!$this->canAccessModule($user, $course, $module)) {
            return redirect()->route('learning.course', $course)->with('error', 'Access denied.');
         }

         return view('learning.quiz', compact('course', 'module', 'quiz'));
    }

    public function submitQuiz(Request $request, Course $course, Module $module, Quiz $quiz)
    {
        $user = Auth::user();
        $questions = $quiz->questions;
        $score = 0;
        $total = $questions->count();
        
        if ($total == 0) return back()->with('error', 'Quiz has no questions.');

        foreach ($questions as $question) {
            $submitted = $request->input('q_' . $question->id);
            // Support both letter answers (a/b/c/d) and full-text answers
            $options = is_array($question->options) ? $question->options : (json_decode($question->options, true) ?? []);
            $isLetter = in_array($submitted, ['a','b','c','d'], true);
            $submittedText = $isLetter ? ($options[$submitted] ?? null) : $submitted;
            if ($submitted === $question->correct_answer || $submittedText === $question->correct_answer) {
                $score++;
            }
        }

        $percentage = ($score / $total) * 100;
        $passed = $percentage >= $quiz->passing_score;

        // Record Attempt (simplified, could be a separate table if needed multiple attempts)
        // For now, let's just use session or a simple flash, but requirement says "show score".
        // Also requirement says "modules cannot be accessed before completing previous".
        // Assuming completing the quiz completes the lesson?
        // Wait, the quiz belongs to a lesson. Usually passing the quiz marks the lesson as passed or is a requirement.
        // Let's assume if quiz is passed, we can move on.
        
        // Create Quiz Attempt Record (we added this table in migration)
        \DB::table('quiz_attempts')->insert([
            'user_id' => $user->id,
            'quiz_id' => $quiz->id,
            'score' => $percentage,
            'passed' => $passed,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return view('learning.quiz-result', compact('course', 'module', 'quiz', 'percentage', 'passed'));
    }

    private function canAccessModule(User $user, Course $course, Module $targetModule)
    {
        // Get all modules with order less than target
        $previousModules = $course->modules()->where('order', '<', $targetModule->order)->get();

        foreach ($previousModules as $mod) {
            foreach ($mod->lessons as $lesson) {
                if (!$user->completedLessons()->where('lesson_id', $lesson->id)->exists()) {
                    return false;
                }
            }
        }
        return true;
    }
}
