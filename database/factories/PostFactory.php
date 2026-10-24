<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MediaCollection;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->unique()->sentence(),
            'description' => Str::limit(fake()->paragraph(), 156),
            'body' => fake()->paragraphs(4, true),
            'enabled' => true,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Post $post): void {
            $images = File::files(database_path('factories/media/posts'));

            $post->addMedia(fake()->randomElement($images)->getPathname())
                ->preservingOriginal()
                ->toMediaCollection(MediaCollection::Images->value);
        });
    }
}
