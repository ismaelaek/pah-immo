<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyDocument;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PropertyDocument>
 */
class PropertyDocumentFactory extends Factory
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
            'apimo_id' => (string) fake()->unique()->numberBetween(1, 999999999),
            'owner' => fn (array $attributes): int => Property::query()->whereKey($attributes['property_id'])->sole()->apimo_id,
            'name' => fake()->word().'.pdf',
            'url' => fake()->url(),
            'download_url' => fake()->url(),
            'filesize' => (string) fake()->numberBetween(1000, 5000000),
            'internet' => '1',
            'child' => '0',
            'extranet' => '0',
            'private' => '0',
            'created_at' => fake()->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
            'updated_at' => fake()->dateTimeBetween('-1 year')->format('Y-m-d H:i:s'),
        ];
    }
}
