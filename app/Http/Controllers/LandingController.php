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
        $agent = request()->header('User-Agent', '');
        $isMobile = preg_match('/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i', $agent) === 1;
        if ($isMobile) {
            if (auth()->check() && auth()->user()->hasRole('student')) {
                return redirect()->route('student.mobile');
            }
        }
        $courses = Course::where('is_published', true)->latest()->take(6)->get();
        $news = News::latest()->take(3)->get();

        // Fetch settings or use defaults
        // Since we might not have settings populated yet, use defaults
        $heroTitle = Setting::where('key', 'hero_title')->value('value') ?? 'Welcome to Our LMS';
        $heroDescription = Setting::where('key', 'hero_description')->value('value') ?? 'Learn anytime, anywhere.';
        $organizerName = Setting::where('key', 'organizer_name')->value('value') ?? 'SKB Institute';

        if ($isMobile) {
            return view('mobile.home', compact('courses', 'news'));
        }

        return view('welcome', compact('courses', 'news', 'heroTitle', 'heroDescription', 'organizerName'));
    }

    public function catalog(Request $request)
    {
        $query = Course::query()->where('is_published', true)->with('teacher');
        if ($request->filled('search')) {
            $s = $request->string('search')->toString();
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                    ->orWhere('description', 'like', "%{$s}%");
            });
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->integer('category_id'));
        }
        if ($request->filled('grade_level')) {
            $query->where('grade_level', $request->string('grade_level')->toString());
        }
        $courses = $query->latest()->paginate(9)->withQueryString();
        $categories = \App\Models\Category::orderBy('name')->get();
        $gradeLevels = collect([
            'Kejar Paket A Kelas 3',
            'Kejar Paket A Kelas 4',
            'Kejar Paket A Kelas 5',
            'Kejar Paket A Kelas 6',
            'Kejar Paket B Kelas 7',
            'Kejar Paket B Kelas 8',
            'Kejar Paket B Kelas 9',
            'Kejar Paket C Kelas 10',
            'Kejar Paket C Kelas 11',
            'Kejar Paket C Kelas 12',
        ]);

        return view('courses.catalog', compact('courses', 'categories', 'gradeLevels'));
    }
}
