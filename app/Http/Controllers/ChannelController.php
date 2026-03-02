<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ChannelController extends Controller
{
    public function show(Channel $channel)
    {
        $channel->load(['user', 'videos' => function ($query) {
            $query->ready()->latest();
        }]);

        return Inertia::render('Channels/Show', [
            'channel' => $channel,
        ]);
    }

    public function create()
    {
        return Inertia::render('Channels/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $user = auth()->user();

        if ($user->channel) {
            return redirect()->route('dashboard')->withErrors(['message' => 'You already have a channel.']);
        }

        $channel = $user->channel()->create([
            'name' => $request->name,
            'slug' => Str::slug($request->name) . '-' . uniqid(),
            'description' => $request->description,
        ]);

        return redirect()->route('channel.show', $channel->slug);
    }
}
