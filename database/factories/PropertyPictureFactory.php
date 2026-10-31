<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyPicture;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyPicture>
 */
class PropertyPictureFactory extends Factory
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
            'apimo_id' => fake()->unique()->numberBetween(1, 999999999),
            'type_id' => 1,
            'rank' => fake()->numberBetween(1, 30),
            'url' => fake()->imageUrl(),
            'width_max' => 1920,
            'height_max' => 1440,
            'internet' => '1',
            'print' => '1',
            'panorama' => false,
            'child' => '0',
            'comments' => [],
        ];
    }
}
