<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\User;

class PublicProfileController extends Controller
{
    public function show(User $user)
    {
        $certificates = Certificate::with('course')
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('public.profile', compact('user', 'certificates'));
    }
}
