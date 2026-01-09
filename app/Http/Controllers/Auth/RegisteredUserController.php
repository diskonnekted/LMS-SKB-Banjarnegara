<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $courses = Course::with('category')->where('is_published', true)->orderBy('title')->get();

        return view('auth.register', compact('courses'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:student,teacher'],
            'enroll_courses' => ['nullable', 'array'],
            'enroll_courses.*' => ['integer', 'exists:courses,id'],
            'teach_courses' => ['nullable', 'array'],
            'teach_courses.*' => ['integer', 'exists:courses,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole($request->role);

        if ($request->role === 'student') {
            $courseIds = collect($request->input('enroll_courses', []))->unique()->values();
            foreach ($courseIds as $cid) {
                $user->enrolledCourses()->syncWithoutDetaching([$cid]);
            }
        } elseif ($request->role === 'teacher') {
            $teachIds = collect($request->input('teach_courses', []))->unique()->values();
            if ($teachIds->count() > 0) {
                Course::whereIn('id', $teachIds)->update(['teacher_id' => $user->id]);
            }
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
