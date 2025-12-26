<?php

namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with('category')->latest()->paginate(10);
        return view('news.index', compact('news'));
    }

    public function create()
    {
        $categories = Category::all();
        $courses = Course::all();
        return view('news.create', compact('categories', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $data = $request->only('title', 'content', 'category_id', 'course_id');
        $data['slug'] = Str::slug($request->title);
        $data['user_id'] = auth()->id();
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('news.index')->with('success', 'News created successfully.');
    }

    public function show(News $news)
    {
        if (!$news->is_published && auth()->id() !== $news->user_id && !auth()->user()->hasRole('admin')) {
            abort(404);
        }
        $recentNews = News::where('id', '!=', $news->id)
            ->where('is_published', true)
            ->latest()
            ->take(5)
            ->get();
            
        return view('news.show', compact('news', 'recentNews'));
    }

    public function edit(News $news)
    {
        $categories = Category::all();
        $courses = Course::all();
        return view('news.edit', compact('news', 'categories', 'courses'));
    }

    public function update(Request $request, News $news)
    {
        Log::info('News Update Request (All):', $request->all());
        
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
            'category_id' => 'required|exists:categories,id',
            'course_id' => 'nullable|exists:courses,id',
        ]);

        $data = $request->only('title', 'content', 'category_id', 'course_id');
        $data['slug'] = Str::slug($request->title);
        $data['is_published'] = $request->has('is_published');

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) {
                Storage::disk('public')->delete($news->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('news.index')->with('success', 'News updated successfully.');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) {
            Storage::disk('public')->delete($news->thumbnail);
        }
        $news->delete();
        return redirect()->route('news.index')->with('success', 'News deleted successfully.');
    }
}
