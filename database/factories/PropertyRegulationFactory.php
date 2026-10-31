<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyRegulation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyRegulation>
 */
class PropertyRegulationFactory extends Factory
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
            'type' => 1,
            'value' => (string) fake()->numberBetween(50, 400),
            'graph_i18ns' => [],
        ];
    }
}
