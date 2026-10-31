<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyComment>
 */
class PropertyCommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'language' => fake()->randomElement(['fr', 'en', 'es']),
            'title' => fake()->sentence(4),
            'comment' => fake()->paragraph(),
        ];
    }
}
