<?php

namespace App\Http\Controllers;

use App\Models\Channel;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function store(Channel $channel)
    {
        $user = auth()->user();

        if ($user->id === $channel->user_id) {
            return back()->withErrors(['message' => 'You cannot subscribe to your own channel.']);
        }

        $subscription = Subscription::where('user_id', $user->id)
            ->where('channel_id', $channel->id)
            ->first();

        if ($subscription) {
            $subscription->delete();
            $channel->decrement('subscribers_count');
        } else {
            Subscription::create([
                'user_id' => $user->id,
                'channel_id' => $channel->id,
            ]);
            $channel->increment('subscribers_count');
        }

        return back();
    }
}
