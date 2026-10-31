<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Agency;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Property>
 */
class PropertyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = fake()->dateTimeBetween('-2 years')->format('Y-m-d H:i:s');

        return [
            'apimo_id' => fake()->unique()->numberBetween(100000, 99999999),
            'reference' => fake()->numerify('#####'),
            'agency' => fake()->numberBetween(1, 99999),
            'quality' => fake()->numberBetween(0, 100),
            'step' => 1,
            'status' => 1,
            'group' => 1,
            'category' => fake()->randomElement([1, 2]),
            'type' => fake()->numberBetween(1, 10),
            'subtype' => fake()->numberBetween(1, 40),
            'publish_address' => false,
            'country' => 'FR',
            'city_id' => fake()->numberBetween(1, 50000),
            'city_name' => fake()->city(),
            'city_zipcode' => fake()->postcode(),
            'longitude' => fake()->longitude(-1, 7),
            'latitude' => fake()->latitude(43, 49),
            'radius' => 500,
            'altitude' => 0,
            'area_unit' => 1,
            'area_value' => fake()->randomFloat(2, 20, 400),
            'plot_serviced_plot' => false,
            'rooms' => fake()->numberBetween(1, 8),
            'bedrooms' => fake()->numberBetween(0, 6),
            'sleeps' => 0,
            'price_value' => fake()->numberBetween(100000, 2000000),
            'price_hide' => false,
            'price_currency' => 'EUR',
            'rates' => [],
            'view_landscape' => [],
            'activities' => [],
            'orientations' => [],
            'tags' => [],
            'tags_customized' => [],
            'financial' => [],
            'created_at' => $date,
            'created_by' => (string) fake()->numberBetween(1, 9999),
            'updated_at' => $date,
            'updated_by' => (string) fake()->numberBetween(1, 9999),
        ];
    }

    public function forAgency(Agency $agency): static
    {
        return $this->state(fn (): array => [
            'agency' => (int) $agency->apimo_id,
        ]);
    }

    public function childOf(Property $parent): static
    {
        return $this->state(fn (): array => [
            'agency' => $parent->agency,
            'parent' => $parent->apimo_id,
        ]);
    }
}
