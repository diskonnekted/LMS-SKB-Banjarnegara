<?php

use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ExamAttemptController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\LearningController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\TeacherExamAttemptController;
use App\Http\Controllers\TeacherExamController;
use App\Http\Controllers\TeacherExamQuestionController;
use App\Http\Controllers\TeacherQuizAttemptController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/icons/icon-{size}.png', [IconController::class, 'icon'])->whereNumber('size')->name('icons.dynamic');
Route::get('/mobile/home', function () {
    $courses = \App\Models\Course::where('is_published', true)->latest()->take(6)->get();
    $news = \App\Models\News::latest()->take(3)->get();

    return view('mobile.home', compact('courses', 'news'));
})->name('mobile.home');

// Public Profile & Certificate Verification
Route::get('/profiles/{user}', [App\Http\Controllers\PublicProfileController::class, 'show'])->name('profiles.public');
Route::get('/certificates/{code}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');
Route::get('/courses/all', [LandingController::class, 'catalog'])->name('courses.catalog');
// Public Course Preview
Route::get('/courses/{course}', [CourseController::class, 'show'])->whereNumber('course')->name('courses.show');

Route::middleware(['role:admin|teacher'])->group(function () {
    Route::resource('news', NewsController::class)->except(['show']);
});

Route::get('/news/{news}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $agent = request()->header('User-Agent', '');
    $isMobile = preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $agent) === 1;
    if ($isMobile && $user && $user->hasRole('student')) {
        return redirect()->route('student.mobile');
    }
    $data = [];

    if ($user->hasRole('admin')) {
        $data['total_students'] = \App\Models\User::role('student')->count();
        $data['total_teachers'] = \App\Models\User::role('teacher')->count();
        $data['total_courses'] = \App\Models\Course::count();
        $data['users'] = \App\Models\User::latest()->paginate(10);
        $data['recent_courses'] = \App\Models\Course::with('teacher')->latest()->take(6)->get();
    } elseif ($user->hasRole('teacher')) {
        $teacherCourses = \App\Models\Course::where('teacher_id', $user->id)
            ->with(['modules.lessons.quiz', 'students'])
            ->get();

        $data['my_courses'] = $teacherCourses->count();

        // Calculate progress and grades for each student in each course
        foreach ($teacherCourses as $course) {
            $courseLessons = $course->modules->flatMap->lessons;
            $courseLessonIds = $courseLessons->pluck('id');
            $totalLessons = $courseLessonIds->count();
            $quizIds = $courseLessons->pluck('quiz.id')->filter();

            foreach ($course->students as $student) {
                // Progress
                $completedCount = \DB::table('lesson_user')
                    ->where('user_id', $student->id)
                    ->whereIn('lesson_id', $courseLessonIds)
                    ->where('completed', true)
                    ->count();

                $student->progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;

                // Grades (Average of all quizzes in course)
                $attempts = \App\Models\QuizAttempt::where('user_id', $student->id)
                    ->whereIn('quiz_id', $quizIds)
                    ->get();

                $student->quiz_average = $attempts->isNotEmpty() ? round($attempts->avg('score')) : '-';
            }
        }

        $data['teacher_courses'] = $teacherCourses;
        $data['my_students'] = $teacherCourses->flatMap->students->unique('id')->count();
    } elseif ($user->hasRole('student')) {
        $myCourses = $user->enrolledCourses()->with('teacher')->get();
        $data['enrolled_courses_count'] = $myCourses->count();
        $data['completed_courses_count'] = $myCourses->filter(fn ($course) => $course->pivot && $course->pivot->completed_at)->count();
        $data['my_courses'] = $myCourses;
        $data['student_grade_levels'] = $myCourses->pluck('grade_level')->filter()->unique()->values();

        $enrolledCourseIds = $myCourses->pluck('id')->values();
        $studentGradeLevels = $data['student_grade_levels'];
        $now = now();

        $data['available_exams'] = \App\Models\Exam::query()
            ->with(['teacher', 'course'])
            ->where('is_published', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->where(function ($q) use ($enrolledCourseIds) {
                $q->whereNull('course_id');
                if ($enrolledCourseIds->isNotEmpty()) {
                    $q->orWhereIn('course_id', $enrolledCourseIds);
                }
            })
            ->where(function ($q) use ($studentGradeLevels) {
                $q->whereNull('grade_level');
                if ($studentGradeLevels->isNotEmpty()) {
                    $q->orWhereIn('grade_level', $studentGradeLevels);
                }
            })
            ->orderBy('starts_at')
            ->orderBy('id', 'desc')
            ->get();

        $data['exam_attempts'] = \App\Models\ExamAttempt::query()
            ->where('user_id', $user->id)
            ->with(['exam.teacher', 'exam.course'])
            ->latest('submitted_at')
            ->take(10)
            ->get();
    }

    return view('dashboard', $data);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/mobile', function () {
        return view('student.mobile');
    })->middleware(['role:student'])->name('student.mobile');

    // Admin & Teacher Routes
    Route::middleware(['role:admin|teacher'])->group(function () {
        Route::get('/teacher/manual', function () {
            return view('teacher.manual.index');
        })->name('teacher.manual.index');

        Route::get('/teacher/latex-guide', function () {
            return view('teacher.latex-guide');
        })->name('teacher.latex-guide');

        Route::get('/teacher/exams', [TeacherExamController::class, 'index'])->name('teacher.exams.index');
        Route::get('/teacher/exams/create', [TeacherExamController::class, 'create'])->name('teacher.exams.create');
        Route::post('/teacher/exams', [TeacherExamController::class, 'store'])->name('teacher.exams.store');
        Route::get('/teacher/exams/{exam}/edit', [TeacherExamController::class, 'edit'])->name('teacher.exams.edit');
        Route::get('/teacher/exams/{exam}/qr', [TeacherExamController::class, 'downloadQr'])->name('teacher.exams.qr.download');
        Route::put('/teacher/exams/{exam}', [TeacherExamController::class, 'update'])->name('teacher.exams.update');
        Route::delete('/teacher/exams/{exam}', [TeacherExamController::class, 'destroy'])->name('teacher.exams.destroy');

        Route::post('/teacher/exams/{exam}/questions', [TeacherExamQuestionController::class, 'store'])->name('teacher.exams.questions.store');
        Route::get('/teacher/exam-questions/{examQuestion}/edit', [TeacherExamQuestionController::class, 'edit'])->name('teacher.exam-questions.edit');
        Route::put('/teacher/exam-questions/{examQuestion}', [TeacherExamQuestionController::class, 'update'])->name('teacher.exam-questions.update');
        Route::delete('/teacher/exam-questions/{examQuestion}', [TeacherExamQuestionController::class, 'destroy'])->name('teacher.exam-questions.destroy');

        Route::get('/teacher/exams/{exam}/attempts', [TeacherExamAttemptController::class, 'index'])->name('teacher.exams.attempts.index');
        Route::get('/teacher/exam-attempts/{attempt}', [TeacherExamAttemptController::class, 'show'])->name('teacher.exams.attempts.show');

        Route::resource('courses.modules', ModuleController::class)->shallow();
        Route::post('/modules/{module}/lessons/editor-images', [LessonController::class, 'uploadEditorImage'])->name('modules.lessons.editor-images');
        Route::resource('modules.lessons', LessonController::class)->shallow();
        Route::resource('lessons.quizzes', QuizController::class)->shallow();
        Route::resource('quizzes.questions', QuestionController::class)->shallow();


        Route::get('/teacher/quizzes/{quiz}/attempts', [TeacherQuizAttemptController::class, 'index'])->name('teacher.quizzes.attempts.index');
        Route::get('/teacher/quiz-attempts/{attempt}', [TeacherQuizAttemptController::class, 'show'])->name('teacher.quizzes.attempts.show');
        Route::post('/teacher/quiz-attempt-answers/{answer}/grade', [TeacherQuizAttemptController::class, 'gradeAnswer'])->name('teacher.quiz-attempt-answers.grade');
    });

    // Admin Only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/manual', function () {
            return view('admin.manual.index');
        })->name('admin.manual.index');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');

        // Admin User Management
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
        Route::post('/admin/users', [AdminUserController::class, 'store'])->name('admin.users.store');
        Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::get('/admin/users/{user}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
        Route::patch('/admin/users/{user}', [AdminUserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    });

    // Student / Learning Routes
    Route::get('/student/manual', function () {
        return view('student.manual.index');
    })->middleware(['role:student'])->name('student.manual.index');

    Route::resource('courses', CourseController::class)->except(['show']);
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');

    Route::get('/learning/{course}', [LearningController::class, 'index'])->name('learning.course');
    Route::get('/learning/{course}/modules/{module}/lessons/{lesson}', [LearningController::class, 'show'])->name('learning.lesson');
    Route::post('/learning/{course}/modules/{module}/lessons/{lesson}/complete', [LearningController::class, 'complete'])->name('learning.complete');

    Route::get('/learning/{course}/modules/{module}/quizzes/{quiz}', [LearningController::class, 'quiz'])->name('learning.quiz');
    Route::post('/learning/{course}/modules/{module}/quizzes/{quiz}', [LearningController::class, 'submitQuiz'])->name('learning.quiz.submit');

    Route::get('/courses/{course}/certificate', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');

    Route::get('/exams/{code}', [ExamAttemptController::class, 'take'])->name('exams.take');
    Route::post('/exams/{code}', [ExamAttemptController::class, 'submit'])->name('exams.submit');
    Route::get('/exams/attempts/{attempt}', [ExamAttemptController::class, 'result'])->name('exams.result');
    Route::get('/exams/attempts/{attempt}/pdf', [ExamAttemptController::class, 'downloadPdf'])->name('exams.attempts.pdf');
});

require __DIR__.'/auth.php';

// Convenience GET logout to avoid 419 when users hit /logout directly
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout.get');
