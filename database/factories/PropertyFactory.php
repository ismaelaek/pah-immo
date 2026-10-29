<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Equipment;
use App\Enums\MediaCollection;
use App\Enums\OfferType;
use App\Enums\PropertyType;
use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

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
        $offerType = fake()->randomElement(OfferType::cases());

        return [
            'title' => fake()->unique()->sentence(4),
            'reference' => 'PAH-'.fake()->unique()->numerify('#####'),
            'description' => fake()->text(156),
            'body' => fake()->paragraphs(4, true),
            'type' => fake()->randomElement(PropertyType::cases()),
            'offer_type' => $offerType,
            'price' => $offerType === OfferType::Rent
                ? fake()->numberBetween(500, 5000)
                : fake()->numberBetween(80_000, 1_500_000),
            'area' => fake()->numberBetween(20, 400),
            'bedrooms' => fake()->numberBetween(1, 6),
            'bathrooms' => fake()->numberBetween(1, 4),
            'neighborhood' => fake()->streetName(),
            'city' => fake()->randomElement(['Paris', 'Lyon', 'Bordeaux', 'Nice']),
            'latitude' => fake()->latitude(43, 49),
            'longitude' => fake()->longitude(-1, 7),
            'equipment' => fake()->randomElements(Equipment::cases(), fake()->numberBetween(2, 6)),
        ];
    }

    public function forSale(): static
    {
        return $this->state(fn (): array => [
            'offer_type' => OfferType::Sale,
            'price' => fake()->numberBetween(80_000, 1_500_000),
        ]);
    }

    public function forRent(): static
    {
        return $this->state(fn (): array => [
            'offer_type' => OfferType::Rent,
            'price' => fake()->numberBetween(500, 5000),
        ]);
    }

    public function withImages(int $count = 3): static
    {
        return $this->afterCreating(function (Property $property) use ($count): void {
            $images = File::files(database_path('factories/media/posts'));

            foreach (fake()->randomElements($images, min($count, count($images))) as $image) {
                $property->addMedia($image->getPathname())
                    ->preservingOriginal()
                    ->toMediaCollection(MediaCollection::Images->value);
            }
        });
    }
}
