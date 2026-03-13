<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\WatchHistory;
use Illuminate\Http\Request;

class WatchHistoryController extends Controller
{
    public function index()
    {
        $history = auth()->user()->watchHistories()
            ->with(['video.channel'])
            ->latest('watched_at')
            ->paginate(24);

        return response()->json([
            'history' => $history,
        ]);
    }

    public function store(Request $request, Video $video)
    {
        $request->validate([
            'progress' => 'required|integer|min:0',
        ]);

        $user = auth()->user();

        WatchHistory::updateOrCreate(
            ['user_id' => $user->id, 'video_id' => $video->id],
            [
                'progress_seconds' => $request->progress,
                'watched_at' => now(),
            ]
        );

        return response()->json(['status' => 'success']);
    }
}
