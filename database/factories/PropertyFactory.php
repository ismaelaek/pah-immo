<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Equipment;
use App\Enums\Locale;
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
            'title' => $this->translations(fn (): string => fake()->unique()->sentence(4)),
            'reference' => 'PAH-'.fake()->unique()->numerify('#####'),
            'description' => $this->translations(fn (): string => fake()->text(156)),
            'body' => $this->translations(fn (): string => collect(range(1, 4))->map(fn (): string => fake()->paragraph())->implode("\n\n")),
            'type' => fake()->randomElement(PropertyType::cases()),
            'offer_type' => $offerType,
            'price' => $offerType === OfferType::Rent
                ? $this->rentPrice()
                : $this->salePrice(),
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
            'price' => $this->salePrice(),
        ]);
    }

    public function forRent(): static
    {
        return $this->state(fn (): array => [
            'offer_type' => OfferType::Rent,
            'price' => $this->rentPrice(),
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

    /**
     * A sale price in euros, rounded to the thousand.
     */
    private function salePrice(): int
    {
        return fake()->numberBetween(80, 1_500) * 1_000;
    }

    /**
     * A monthly rent in euros, rounded to ten.
     */
    private function rentPrice(): int
    {
        return fake()->numberBetween(50, 500) * 10;
    }

    /**
     * Generate one value per supported locale.
     *
     * @param  callable(): string  $value
     * @return array<string, string>
     */
    private function translations(callable $value): array
    {
        return collect(Locale::values())
            ->mapWithKeys(fn (string $locale): array => [$locale => $value()])
            ->all();
    }
}
