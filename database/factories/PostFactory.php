<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Locale;
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
            'title' => $this->translations(fn (): string => fake()->unique()->sentence()),
            'description' => $this->translations(fn (): string => Str::limit(fake()->paragraph(), 156)),
            'body' => $this->translations(fn (): string => collect(range(1, 4))->map(fn (): string => fake()->paragraph())->implode("\n\n")),
            'enabled' => true,
            'views' => 0,
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

    /**
     * The same kind of value in every public language.
     *
     * @param  callable(): string  $value
     * @return array<string, string>
     */
    private function translations(callable $value): array
    {
        return collect(Locale::values())
            ->mapWithKeys(fn (string $locale): array => [$locale => $value()])
            ->all();
    }
}
