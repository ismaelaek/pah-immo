<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Post;
use App\Models\Property;
use App\Models\PropertyComment;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()
            ->withAvatar()
            ->create([
                'name' => 'Admin User',
                'email' => 'admin@pah-immo.com',
            ]);

        $users = User::factory(3)->withAvatar()->create();

        Post::factory()
            ->recycle($users)
            ->count(6)
            ->create();

        Property::factory()
            ->has(PropertyComment::factory()->state(['language' => 'fr']), 'comments')
            ->count(9)
            ->create();

        // Messages
        Message::factory()
            ->count(10)
            ->create();
    }
}
