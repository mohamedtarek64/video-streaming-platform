<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\Comment;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function voteVideo(Video $video, $type)
    {
        return $this->vote($video, $type);
    }

    public function voteComment(Comment $comment, $type)
    {
        return $this->vote($comment, $type);
    }

    protected function vote($votable, $type)
    {
        $type = $type == 'up' ? 1 : -1;
        $user = auth()->user();

        $existingVote = $votable->votes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            if ($existingVote->type == $type) {
                $existingVote->delete();
            } else {
                $existingVote->update(['type' => $type]);
            }
        } else {
            $votable->votes()->create([
                'user_id' => $user->id,
                'type' => $type,
            ]);
        }

        return back();
    }
}
