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
            'channel' => array_merge($channel->toArray(), [
                'can_edit' => auth()->id() === $channel->user_id,
            ]),
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

    public function edit()
    {
        $user = auth()->user();
        $channel = $user->channel;

        if (!$channel) {
            return redirect()->route('channel.create');
        }

        return Inertia::render('Channels/Settings', [
            'channel' => $channel,
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $channel = $user->channel;

        if (!$channel) {
            return back()->withErrors(['message' => 'No channel found.']);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'avatar' => 'nullable|image|max:2048',
            'banner' => 'nullable|image|max:5120',
        ]);

        $channel->name = $request->name;
        $channel->description = $request->description;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $channel->avatar_path = '/storage/' . $path;
        }

        if ($request->hasFile('banner')) {
            $path = $request->file('banner')->store('banners', 'public');
            $channel->banner_path = '/storage/' . $path;
        }

        $channel->save();

        return redirect()->route('channel.show', $channel->slug)->with('success', 'Channel updated successfully.');
    }
}
