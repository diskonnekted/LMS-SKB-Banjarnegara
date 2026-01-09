<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function index(Course $course)
    {
        $user = Auth::user();

        // Ensure user is enrolled
        if (! $user->enrolledCourses()->where('course_id', $course->id)->exists()) {
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

        if (! $firstModule) {
            return redirect()->route('courses.show', $course)->with('error', 'This course has no content yet.');
        }

        $firstLesson = $firstModule->lessons()->orderBy('order')->first();

        if (! $firstLesson) {
            return redirect()->route('courses.show', $course)->with('error', 'This course has no content yet.');
        }

        // Ideally redirect to last accessed lesson, but for now first is fine or first incomplete
        return redirect()->route('learning.lesson', [$course, $firstModule, $firstLesson]);
    }

    public function show(Course $course, Module $module, Lesson $lesson)
    {
        $user = Auth::user();

        // 1. Authorization: Enrolled?
        if (! $user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return redirect()->route('courses.show', $course);
        }

        // 2. Sequential Access Logic
        if (! $this->canAccessModule($user, $course, $module)) {
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
        if (! $user->completedLessons()->where('lesson_id', $lesson->id)->exists()) {
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
        if (! $this->canAccessModule($user, $course, $module)) {
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
        $answerRows = [];
        $now = now();

        if ($total == 0) {
            return back()->with('error', 'Quiz has no questions.');
        }

        foreach ($questions as $question) {
            $submitted = $request->input('q_'.$question->id);
            $isCorrect = false;

            switch ($question->type) {
                case 'multiple_choice':
                case 'true_false':
                    if ($submitted == $question->correct_answer) {
                        $isCorrect = true;
                    }
                    break;

                case 'multiple_response':
                    $corrects = json_decode($question->correct_answer, true) ?? [];
                    if (is_array($submitted)) {
                        sort($submitted);
                        sort($corrects);
                        if ($submitted == $corrects) {
                            $isCorrect = true;
                        }
                    }
                    break;

                case 'short_answer':
                case 'numeric':
                    if (strcasecmp(trim((string) $submitted), trim((string) $question->correct_answer)) === 0) {
                        $isCorrect = true;
                    }
                    break;

                case 'matching':
                case 'drag_drop':
                    $options = is_array($question->options) ? $question->options : (json_decode($question->options, true) ?? []);
                    $allMatch = true;
                    if (is_array($submitted)) {
                        foreach ($options as $idx => $pair) {
                            $userVal = $submitted[$idx] ?? null;
                            if ($userVal !== $pair['right']) {
                                $allMatch = false;
                                break;
                            }
                        }
                        if ($allMatch) {
                            $isCorrect = true;
                        }
                    }
                    break;

                case 'sequencing':
                    $correctOrder = json_decode($question->correct_answer, true) ?? [];
                    $allCorrect = true;
                    if (is_array($submitted)) {
                        foreach ($correctOrder as $index => $item) {
                            $expectedOrder = $index + 1;
                            // Handle PHP's input name mangling (spaces/dots to underscores)
                            $lookupKey = str_replace([' ', '.'], '_', $item);
                            // Try both mangled and raw just in case
                            $userOrder = $submitted[$lookupKey] ?? $submitted[$item] ?? null;

                            if ($userOrder != $expectedOrder) {
                                $allCorrect = false;
                                break;
                            }
                        }
                        if ($allCorrect) {
                            $isCorrect = true;
                        }
                    } else {
                        $allCorrect = false;
                    }
                    break;

                case 'essay':
                    // Auto-grade participation for now
                    if (! empty(trim($submitted)) && strlen(trim($submitted)) > 5) {
                        $isCorrect = true;
                    }
                    break;

                default:
                    // Fallback for legacy data
                    $options = is_array($question->options) ? $question->options : (json_decode($question->options, true) ?? []);
                    $isLetter = in_array($submitted, ['a', 'b', 'c', 'd', 'e'], true);
                    $submittedText = $isLetter ? ($options[$submitted] ?? null) : $submitted;
                    if ($submitted === $question->correct_answer || $submittedText === $question->correct_answer) {
                        $isCorrect = true;
                    }
                    break;
            }

            if ($isCorrect) {
                $score++;
            }

            $storedAnswer = null;
            if (is_array($submitted)) {
                $storedAnswer = json_encode($submitted);
            } elseif ($submitted !== null) {
                $storedAnswer = (string) $submitted;
            }

            $answerRows[] = [
                'question_id' => $question->id,
                'answer' => $storedAnswer,
                'is_correct' => $isCorrect,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $percentage = ($score / $total) * 100;
        $passed = $percentage >= $quiz->passing_score;

        \DB::transaction(function () use ($user, $quiz, $percentage, $passed, $answerRows) {
            $attempt = QuizAttempt::create([
                'user_id' => $user->id,
                'quiz_id' => $quiz->id,
                'score' => $percentage,
                'passed' => $passed,
            ]);

            if (! empty($answerRows)) {
                $rows = array_map(function (array $row) use ($attempt) {
                    $row['quiz_attempt_id'] = $attempt->id;

                    return $row;
                }, $answerRows);

                \DB::table('quiz_attempt_answers')->insert($rows);
            }
        });

        return view('learning.quiz-result', compact('course', 'module', 'quiz', 'percentage', 'passed'));
    }

    private function canAccessModule(User $user, Course $course, Module $targetModule)
    {
        // Get all modules with order less than target
        $previousModules = $course->modules()->where('order', '<', $targetModule->order)->get();

        foreach ($previousModules as $mod) {
            foreach ($mod->lessons as $lesson) {
                if (! $user->completedLessons()->where('lesson_id', $lesson->id)->exists()) {
                    return false;
                }
            }
        }

        return true;
    }
}
