<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('news.index', compact('news'));
    }

    public function create()
    {
        return view('news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('title', 'content');
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
        return view('news.show', compact('news'));
    }

    public function edit(News $news)
    {
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        Log::info('News Update Request (All):', $request->all());
        Log::info('News Update Request (Files):', $request->allFiles());
        if (isset($_FILES)) {
            Log::info('Raw _FILES:', $_FILES);
        }
        
        if ($request->hasFile('thumbnail')) {
             $file = $request->file('thumbnail');
             Log::info('File details:', [
                 'original_name' => $file->getClientOriginalName(),
                 'mime_type' => $file->getMimeType(),
                 'size' => $file->getSize(),
                 'valid' => $file->isValid(),
                 'error' => $file->getError(),
             ]);
        } else {
             Log::info('No thumbnail file detected in request.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('title', 'content');
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
