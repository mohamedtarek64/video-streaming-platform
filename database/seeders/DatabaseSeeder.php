<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        $channel = $user->channel()->create([
            'name' => 'Code & Coffee',
            'slug' => 'code-coffee',
            'description' => 'A channel dedicated to coding tutorials and coffee reviews.',
            'subscribers_count' => 12500,
        ]);

        $videos = [
            [
                'title' => 'Building a Video Streaming Platform with Laravel',
                'description' => "In this video, we'll build a full-featured video streaming platform from scratch using Laravel, Vue 3, and Tailwind CSS.\n\nTechnogies used:\n- Laravel 11\n- Vue.js 3 (Inertia)\n- TailwindCSS\n- PostgreSQL",
                'category' => 'Tech',
                'views' => 45200,
            ],
            [
                'title' => 'The Perfect Espresso Shot: A Guide',
                'description' => "Learn how to dial in the perfect espresso shot at home. We'll cover grind size, temperature, and timing.",
                'category' => 'Lifestyle',
                'views' => 12000,
            ],
            [
                'title' => 'Vue 3 Composition API Explained',
                'description' => "Struggling with the transition to Composition API? This video breaks down the core concepts and why you should use it.",
                'category' => 'Education',
                'views' => 8900,
            ],
            [
                'title' => 'Morning Routine for Software Engineers',
                'description' => "See how a senior software engineer prepares for the day to stay productive and focused.",
                'category' => 'Lifestyle',
                'views' => 54000,
            ],
        ];

        foreach ($videos as $videoData) {
            $uid = uniqid();
            $channel->videos()->create([
                'uid' => $uid,
                'title' => $videoData['title'],
                'slug' => \Illuminate\Support\Str::slug($videoData['title']) . '-' . $uid,
                'description' => $videoData['description'],
                'category' => $videoData['category'],
                'views' => $videoData['views'],
                'status' => 'ready',
                'thumbnail_path' => 'https://picsum.photos/seed/' . $uid . '/1280/720',
                'original_filename' => 'video.mp4',
            ]);
        }

        // Create some more random users with channels and videos
        User::factory(5)->create()->each(function ($u) {
            $c = $u->channel()->create([
                'name' => $u->name . "'s Channel",
                'slug' => \Illuminate\Support\Str::slug($u->name) . '-' . uniqid(),
                'description' => 'Welcome to my channel!',
                'subscribers_count' => rand(100, 5000),
            ]);

            for ($i = 0; $i < 3; $i++) {
                $uid = uniqid();
                $title = "Awesome Video #" . ($i + 1);
                $c->videos()->create([
                    'uid' => $uid,
                    'title' => $title,
                    'slug' => \Illuminate\Support\Str::slug($title) . '-' . $uid,
                    'description' => 'This is a random video by ' . $u->name,
                    'status' => 'ready',
                    'thumbnail_path' => 'https://picsum.photos/seed/' . $uid . '/1280/720',
                    'original_filename' => 'random.mp4',
                    'views' => rand(100, 10000),
                ]);
            }
        });
    }
}
