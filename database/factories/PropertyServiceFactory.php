<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyService>
 */
class PropertyServiceFactory extends Factory
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
            'type' => fake()->numberBetween(1, 100),
        ];
    }
}
