<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function create(Module $module)
    {
        if (!Auth::user()->hasRole('admin') && $module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        return view('lessons.create', compact('module'));
    }

    public function store(Request $request, Module $module)
    {
        if (!Auth::user()->hasRole('admin') && $module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,text,pdf,ppt,doc,xls',
            'content' => 'nullable|string', // For text or embed code
            'file' => 'nullable|file|max:10240', // 10MB limit
            'basic_competency' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
        ]);

        $slug = Str::slug($request->title);
        
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('lessons', 'public');
        }

        $content = $request->content;
        if ($request->type === 'video' && $content) {
            if (!Str::contains($content, '<iframe')) {
                $content = $this->youtubeEmbedHtml($content);
            }
        }

        $module->lessons()->create([
            'title' => $request->title,
            'slug' => $slug,
            'type' => $request->type,
            'content' => $content,
            'basic_competency' => $request->basic_competency,
            'learning_objectives' => $request->learning_objectives,
            'file_path' => $filePath,
            'order' => $module->lessons()->count() + 1,
        ]);

        return redirect()->route('courses.modules.index', $module->course)->with('success', 'Lesson created successfully.');
    }

    public function edit(Lesson $lesson)
    {
         if (!Auth::user()->hasRole('admin') && $lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        return view('lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        if (!Auth::user()->hasRole('admin') && $lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,text,pdf,ppt,doc,xls',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:10240',
            'basic_competency' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
        ]);

        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('lessons', 'public');
            $lesson->file_path = $filePath;
        }

        $content = $request->content;
        if ($request->type === 'video' && $content) {
            if (!Str::contains($content, '<iframe')) {
                $content = $this->youtubeEmbedHtml($content);
            }
        }

        $lesson->update([
            'title' => $request->title,
            'type' => $request->type,
            'content' => $content,
            'basic_competency' => $request->basic_competency,
            'learning_objectives' => $request->learning_objectives,
        ]);

        return redirect()->route('courses.modules.index', $lesson->module->course)->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson)
    {
        if (!Auth::user()->hasRole('admin') && $lesson->module->course->teacher_id !== Auth::id()) {
            abort(403);
        }
        $lesson->delete();
        return back()->with('success', 'Lesson deleted.');
    }

    private function youtubeEmbedHtml(string $input): string
    {
        $url = trim($input);
        $videoId = null;
        if (Str::startsWith($url, ['https://youtu.be/', 'http://youtu.be/'])) {
            $videoId = trim(Str::after($url, 'youtu.be/'));
        } elseif (Str::contains($url, 'youtube.com/watch')) {
            $query = parse_url($url, PHP_URL_QUERY);
            parse_str($query ?? '', $params);
            $videoId = $params['v'] ?? null;
        } elseif (Str::contains($url, 'youtube.com/embed/')) {
            $videoId = trim(Str::after($url, 'embed/'));
        }
        if ($videoId) {
            $embedUrl = 'https://www.youtube.com/embed/' . $videoId;
            return '<iframe width="100%" height="100%" src="' . e($embedUrl) . '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        return $input;
    }
}
