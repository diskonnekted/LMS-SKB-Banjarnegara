<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\LearningController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LandingController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\IconController;
use Illuminate\Support\Facades\Auth;

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
        $data['enrolled_courses_count'] = $user->enrolledCourses()->count();
        $data['completed_courses_count'] = $user->enrolledCourses()->wherePivotNotNull('completed_at')->count();
        $data['my_courses'] = $user->enrolledCourses()->with('teacher')->get();
        $data['student_grade_levels'] = $user->enrolledCourses()->pluck('grade_level')->filter()->unique()->values();
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

        Route::resource('courses.modules', ModuleController::class)->shallow();
        Route::resource('modules.lessons', LessonController::class)->shallow();
        Route::resource('lessons.quizzes', QuizController::class)->shallow();
        Route::resource('quizzes.questions', QuestionController::class)->shallow();
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
});

require __DIR__.'/auth.php';

// Convenience GET logout to avoid 419 when users hit /logout directly
Route::get('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('home');
})->middleware('auth')->name('logout.get');
