<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TeacherExamController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Exam::query()->withCount('questions')->latest();
        if (! $user->hasRole('admin')) {
            $query->where('teacher_id', $user->id);
        }
        $exams = $query->paginate(15);

        return view('teacher.exams.index', compact('exams'));
    }

    public function create()
    {
        $courses = $this->getSelectableCourses();
        $gradeLevels = $this->getGradeLevels();

        return view('teacher.exams.create', compact('courses', 'gradeLevels'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $courseIdRule = Rule::exists('courses', 'id');
        if (! $user->hasRole('admin')) {
            $courseIdRule = $courseIdRule->where('teacher_id', $user->id);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => [
                'nullable',
                'integer',
                $courseIdRule,
            ],
            'grade_level' => 'nullable|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
            'duration_minutes' => 'nullable|integer|min:1|max:1440',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_published' => 'sometimes|boolean',
        ]);

        $course = null;
        if ($request->course_id) {
            $course = $this->getSelectableCoursesQuery()->whereKey($request->course_id)->firstOrFail();
        }

        $gradeLevel = $request->grade_level;
        if (($gradeLevel === null || trim((string) $gradeLevel) === '') && $course) {
            $gradeLevel = $course->grade_level;
        }

        $exam = Exam::create([
            'teacher_id' => Auth::id(),
            'course_id' => $course?->id,
            'title' => $request->title,
            'description' => $request->description,
            'grade_level' => $gradeLevel,
            'passing_score' => $request->passing_score,
            'duration_minutes' => $request->duration_minutes,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_published' => (bool) $request->boolean('is_published'),
        ]);

        return redirect()->route('teacher.exams.edit', $exam)->with('success', 'Ujian dibuat. Silakan tambah soal.');
    }

    public function edit(Exam $exam)
    {
        $this->authorizeOwner($exam);

        $exam->load(['questions' => function ($query) {
            $query->orderBy('order')->orderBy('id');
        }]);

        $courses = $this->getSelectableCourses();
        $gradeLevels = $this->getGradeLevels();

        return view('teacher.exams.edit', compact('exam', 'courses', 'gradeLevels'));
    }

    public function update(Request $request, Exam $exam)
    {
        $this->authorizeOwner($exam);

        $user = Auth::user();

        $courseIdRule = Rule::exists('courses', 'id');
        if (! $user->hasRole('admin')) {
            $courseIdRule = $courseIdRule->where('teacher_id', $user->id);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => [
                'nullable',
                'integer',
                $courseIdRule,
            ],
            'grade_level' => 'nullable|string|max:255',
            'passing_score' => 'required|integer|min:0|max:100',
            'duration_minutes' => 'nullable|integer|min:1|max:1440',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'is_published' => 'sometimes|boolean',
        ]);

        $course = null;
        if ($request->course_id) {
            $course = $this->getSelectableCoursesQuery()->whereKey($request->course_id)->firstOrFail();
        }

        $gradeLevel = $request->grade_level;
        if (($gradeLevel === null || trim((string) $gradeLevel) === '') && $course) {
            $gradeLevel = $course->grade_level;
        }

        $exam->update([
            'title' => $request->title,
            'description' => $request->description,
            'course_id' => $course?->id,
            'grade_level' => $gradeLevel,
            'passing_score' => $request->passing_score,
            'duration_minutes' => $request->duration_minutes,
            'starts_at' => $request->starts_at,
            'ends_at' => $request->ends_at,
            'is_published' => (bool) $request->boolean('is_published'),
        ]);

        return back()->with('success', 'Ujian diperbarui.');
    }

    public function destroy(Exam $exam)
    {
        $this->authorizeOwner($exam);

        $exam->delete();

        return redirect()->route('teacher.exams.index')->with('success', 'Ujian dihapus.');
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

    private function getGradeLevels(): array
    {
        $fromCourses = Course::query()
            ->whereNotNull('grade_level')
            ->where('grade_level', '!=', '')
            ->distinct()
            ->orderBy('grade_level')
            ->pluck('grade_level')
            ->all();

        $defaults = [];
        foreach (['A' => [1, 2, 3, 4, 5, 6], 'B' => [7, 8, 9], 'C' => [10, 11, 12]] as $paket => $kelasList) {
            foreach ($kelasList as $kelas) {
                $defaults[] = "Kesetaraan Paket {$paket} Kelas {$kelas}";
            }
        }

        $gradeLevels = array_values(array_unique(array_merge($fromCourses, $defaults)));
        sort($gradeLevels);

        return $gradeLevels;
    }

    private function getSelectableCourses(): \Illuminate\Support\Collection
    {
        return $this->getSelectableCoursesQuery()
            ->with('teacher')
            ->orderBy('title')
            ->get();
    }

    private function getSelectableCoursesQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $user = Auth::user();
        $query = Course::query();
        if (! $user->hasRole('admin')) {
            $query->where('teacher_id', $user->id);
        }

        return $query;
    }
}
