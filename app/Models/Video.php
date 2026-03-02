<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'channel_id',
        'uid',
        'title',
        'slug',
        'description',
        'status',
        'duration',
        'views',
        'original_filename',
        'hls_playlist_path',
        'thumbnail_path',
        'category',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function watchHistories()
    {
        return $this->hasMany(WatchHistory::class);
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function votes()
    {
        return $this->morphMany(Vote::class, 'votable');
    }

    public function upVotes()
    {
        return $this->votes()->where('type', 1);
    }

    public function downVotes()
    {
        return $this->votes()->where('type', -1);
    }
}
