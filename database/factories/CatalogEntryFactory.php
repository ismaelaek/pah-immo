<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CatalogEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CatalogEntry>
 */
class CatalogEntryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'catalog' => 'property_type',
            'apimo_id' => fake()->unique()->numberBetween(1, 99999),
            'culture' => 'fr_FR',
            'name' => fake()->word(),
        ];
    }
}
