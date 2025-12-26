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

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/news/{news}', [App\Http\Controllers\NewsController::class, 'show'])->name('news.show');

// Public Profile & Certificate Verification
Route::get('/profiles/{user}', [App\Http\Controllers\PublicProfileController::class, 'show'])->name('profiles.public');
Route::get('/certificates/{code}', [App\Http\Controllers\CertificateController::class, 'verify'])->name('certificates.verify');

Route::middleware(['role:admin'])->group(function () {
    Route::resource('news', NewsController::class);
});

Route::get('/dashboard', function () {
    $user = auth()->user();
    $data = [];

    if ($user->hasRole('admin')) {
        $data['total_students'] = \App\Models\User::role('student')->count();
        $data['total_teachers'] = \App\Models\User::role('teacher')->count();
        $data['total_courses'] = \App\Models\Course::count();
        $data['users'] = \App\Models\User::latest()->paginate(10);
    } elseif ($user->hasRole('teacher')) {
        $data['my_courses'] = \App\Models\Course::where('teacher_id', $user->id)->count();
        // Students enrolled in teacher's courses
        $data['my_students'] = \App\Models\User::whereHas('enrolledCourses', function($q) use ($user) {
            $q->where('teacher_id', $user->id);
        })->count();
    } elseif ($user->hasRole('student')) {
        $data['enrolled_courses_count'] = $user->enrolledCourses()->count();
        $data['completed_courses_count'] = $user->enrolledCourses()->wherePivotNotNull('completed_at')->count();
        $data['my_courses'] = $user->enrolledCourses()->with('teacher')->get();
    }

    return view('dashboard', $data);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin & Teacher Routes
    Route::middleware(['role:admin|teacher'])->group(function () {
        Route::resource('courses.modules', ModuleController::class)->shallow();
        Route::resource('modules.lessons', LessonController::class)->shallow();
        Route::resource('lessons.quizzes', QuizController::class)->shallow();
        Route::resource('quizzes.questions', QuestionController::class)->shallow();
    });

    // Admin Only
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });

    // Student / Learning Routes
    Route::resource('courses', CourseController::class);
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');
    
    Route::get('/learning/{course}', [LearningController::class, 'index'])->name('learning.course');
    Route::get('/learning/{course}/modules/{module}/lessons/{lesson}', [LearningController::class, 'show'])->name('learning.lesson');
    Route::post('/learning/{course}/modules/{module}/lessons/{lesson}/complete', [LearningController::class, 'complete'])->name('learning.complete');
    
    Route::get('/learning/{course}/modules/{module}/quizzes/{quiz}', [LearningController::class, 'quiz'])->name('learning.quiz');
    Route::post('/learning/{course}/modules/{module}/quizzes/{quiz}', [LearningController::class, 'submitQuiz'])->name('learning.quiz.submit');

    Route::get('/courses/{course}/certificate', [App\Http\Controllers\CertificateController::class, 'download'])->name('certificates.download');
});

require __DIR__.'/auth.php';
