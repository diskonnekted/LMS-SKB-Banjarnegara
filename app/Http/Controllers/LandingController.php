<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\News;
use App\Models\Setting;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $courses = Course::where('is_published', true)->latest()->take(6)->get();
        $news = News::latest()->take(3)->get();
        
        // Fetch settings or use defaults
        // Since we might not have settings populated yet, use defaults
        $heroTitle = Setting::where('key', 'hero_title')->value('value') ?? 'Welcome to Our LMS';
        $heroDescription = Setting::where('key', 'hero_description')->value('value') ?? 'Learn anytime, anywhere.';
        $organizerName = Setting::where('key', 'organizer_name')->value('value') ?? 'SKB Institute';

        return view('welcome', compact('courses', 'news', 'heroTitle', 'heroDescription', 'organizerName'));
    }
}
