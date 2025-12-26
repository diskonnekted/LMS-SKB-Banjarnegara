<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Certificate;
use Illuminate\Http\Request;

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
