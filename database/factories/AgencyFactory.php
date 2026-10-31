<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Agency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Agency>
 */
class AgencyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-5 years')->format('Y-m-d H:i:s');

        return [
            'apimo_id' => (string) fake()->unique()->numberBetween(1, 99999),
            'active' => true,
            'name' => fake()->company(),
            'networks' => [],
            'country' => 'FR',
            'logo' => '',
            'pictures' => [],
            'quality' => fake()->numberBetween(0, 100),
            'currency' => 'EUR',
            'rates' => [],
            'partners' => [],
            'stories' => [],
            'subscription' => fake()->word(),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
