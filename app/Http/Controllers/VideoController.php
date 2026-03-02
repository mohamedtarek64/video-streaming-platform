<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function index(Request $request)
    {
        $query = Video::with('channel')->ready();

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        if ($request->filled('category') && $request->category !== 'All') {
            $query->where('category', $request->category);
        }

        $videos = $query->latest()->paginate(12)->withQueryString();

        return Inertia::render('Welcome', [
            'videos' => $videos,
            'filters' => $request->only(['search', 'category']),
        ]);
    }

    public function show(Video $video)
    {
        $video->load(['channel.user', 'comments.user']);
        
        $suggestions = Video::with('channel')
            ->where('id', '!=', $video->id)
            ->ready()
            ->inRandomOrder()
            ->take(8)
            ->get();

        return Inertia::render('Videos/Show', [
            'video' => $video,
            'suggestions' => $suggestions,
        ]);
    }

    public function upload()
    {
        return Inertia::render('Videos/Upload');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video' => 'required|file|mimetypes:video/mp4,video/quicktime|max:51200', // 50MB for demo
            'category' => 'nullable|string',
        ]);

        // In a real app, we'd dispatch a job to process the video with FFmpeg/HLS
        // For now, we'll just save it and mark as ready for demo purposes
        
        $user = auth()->user();
        $channel = $user->channel;

        if (!$channel) {
            return back()->withErrors(['message' => 'You need a channel to upload videos.']);
        }

        $videoFile = $request->file('video');
        $originalFilename = $videoFile->getClientOriginalName();
        $uid = uniqid();
        
        // Simplified storage for demo
        $path = $videoFile->storeAs('videos/' . $uid, $originalFilename, 'public');

        $video = $channel->videos()->create([
            'uid' => $uid,
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->title) . '-' . $uid,
            'description' => $request->description,
            'category' => $request->category,
            'original_filename' => $originalFilename,
            'status' => 'ready', // Simplified
            'thumbnail_path' => 'https://picsum.photos/seed/' . $uid . '/640/360', // Dummy thumbnail
        ]);

        return redirect()->route('videos.show', $video->slug);
    }
}
