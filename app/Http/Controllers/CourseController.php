<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Course::query();

        if ($user->hasRole('admin')) {
            $query->with('teacher');
        } elseif ($user->hasRole('teacher')) {
            $query->where('teacher_id', $user->id);
        } else {
            $query->where('is_published', true)->with('teacher');
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $courses = $query->latest()->paginate(9)->withQueryString();

        return view('courses.index', compact('courses'));
    }

    public function create()
    {
        if (! Auth::user()->hasRole('admin|teacher')) {
            abort(403);
        }
        $categories = Category::all();

        return view('courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (! Auth::user()->hasRole('admin|teacher')) {
            abort(403);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'basic_competency' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'grade_level' => 'required|string',
        ]);

        $slug = Str::slug($request->title);
        $count = Course::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-'.($count + 1);
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Course::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'basic_competency' => $request->basic_competency,
            'learning_objectives' => $request->learning_objectives,
            'thumbnail' => $thumbnailPath,
            'teacher_id' => Auth::id(),
            'is_published' => $request->has('is_published'),
            'category_id' => $request->category_id,
            'grade_level' => $request->grade_level,
        ]);

        return redirect()->route('courses.index')->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load(['modules.lessons.quiz', 'students']);
        $lessons = $course->modules->flatMap->lessons;
        $lessonIds = $lessons->pluck('id');
        $quizIds = $lessons->pluck('quiz.id')->filter();
        $students = $course->students->unique('id');
        $studentStats = $students->map(function ($student) use ($lessonIds, $quizIds) {
            $completedCount = \DB::table('lesson_user')
                ->where('user_id', $student->id)
                ->whereIn('lesson_id', $lessonIds)
                ->where('completed', true)
                ->count();
            $totalLessons = $lessonIds->count();
            $progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
            $attempts = \App\Models\QuizAttempt::where('user_id', $student->id)
                ->whereIn('quiz_id', $quizIds)
                ->orderByDesc('created_at')
                ->get();
            $avg = $attempts->isNotEmpty() ? round($attempts->avg('score')) : null;
            $latest = $attempts->first();

            return [
                'user' => $student,
                'progress' => $progress,
                'attempts' => $attempts->count(),
                'avg' => $avg,
                'latest' => $latest ? round($latest->score) : null,
                'passed_latest' => $latest ? (bool) $latest->passed : null,
            ];
        });

        return view('courses.show', compact('course', 'studentStats'));
    }

    public function edit(Course $course)
    {
        if (! Auth::user()->hasRole('admin') && $course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::all();

        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        if (! Auth::user()->hasRole('admin') && $course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'basic_competency' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'grade_level' => 'required|string',
        ]);

        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
            $course->thumbnail = $thumbnailPath;
        }

        $course->update([
            'title' => $request->title,
            'description' => $request->description,
            'basic_competency' => $request->basic_competency,
            'learning_objectives' => $request->learning_objectives,
            'is_published' => $request->has('is_published'),
            'category_id' => $request->category_id,
            'grade_level' => $request->grade_level,
        ]);

        // Save thumbnail if updated
        if (isset($thumbnailPath)) {
            $course->save();
        }

        return redirect()->route('courses.index')->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        if (! Auth::user()->hasRole('admin') && $course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $course->delete();

        return redirect()->route('courses.index')->with('success', 'Course deleted successfully.');
    }
}
