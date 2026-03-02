<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\VideoController;
use App\Http\Controllers\ChannelController;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\WatchHistoryController;

Route::get('/', [VideoController::class, 'index'])->name('home');
Route::get('/channel/{channel:slug}', [ChannelController::class, 'show'])->name('channel.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('videos', VideoController::class)->except(['index']);
    Route::get('/channel/create', [ChannelController::class, 'create'])->name('channel.create');
    Route::post('/channel', [ChannelController::class, 'store'])->name('channel.store');
    Route::get('/settings/channel', [ChannelController::class, 'edit'])->name('channel.edit');
    Route::post('/settings/channel', [ChannelController::class, 'update'])->name('channel.update');
    
    // Interactions
    Route::post('/videos/{video}/vote/{type}', [VoteController::class, 'voteVideo'])->name('videos.vote');
    Route::post('/comments/{comment}/vote/{type}', [VoteController::class, 'voteComment'])->name('comments.vote');
    Route::post('/channels/{channel}/subscribe', [SubscriptionController::class, 'store'])->name('channels.subscribe');

    // Comments
    Route::post('/videos/{video}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // History
    Route::post('/videos/{video}/history', [WatchHistoryController::class, 'store'])->name('videos.history');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
