<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyArea>
 */
class PropertyAreaFactory extends Factory
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
            'type' => fake()->numberBetween(1, 60),
            'number' => 1,
            'area' => fake()->randomFloat(2, 5, 60),
            'orientations' => [],
            'comments' => [],
            'lot_name' => [],
        ];
    }
}
