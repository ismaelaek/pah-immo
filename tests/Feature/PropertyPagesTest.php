<?php

declare(strict_types=1);

use App\Enums\OfferType;
use App\Enums\PropertyType;
use App\Models\Property;
use Inertia\Testing\AssertableInertia;

test('the properties page lists properties with the available filters', function () {
    Property::factory()->create(['city' => 'Nice']);
    Property::factory()->create(['city' => 'Lyon']);

    $this->get(route('properties', ['locale' => 'en']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Properties')
            ->has('properties.data', 2)
            ->where('properties.total', 2)
            ->where('cities', ['Lyon', 'Nice'])
            ->where('filters', ['type' => '', 'price' => '', 'city' => '', 'rooms' => '']));
});

test('properties can be filtered by type, price, city and rooms', function () {
    $match = Property::factory()->forSale()->create([
        'type' => PropertyType::Villa,
        'price' => 300_000,
        'city' => 'Nice',
        'bedrooms' => 3,
    ]);
    Property::factory()->forSale()->create(['type' => PropertyType::House, 'price' => 300_000, 'city' => 'Nice', 'bedrooms' => 3]);
    Property::factory()->forSale()->create(['type' => PropertyType::Villa, 'price' => 600_000, 'city' => 'Nice', 'bedrooms' => 3]);
    Property::factory()->forSale()->create(['type' => PropertyType::Villa, 'price' => 300_000, 'city' => 'Lyon', 'bedrooms' => 3]);
    Property::factory()->forSale()->create(['type' => PropertyType::Villa, 'price' => 300_000, 'city' => 'Nice', 'bedrooms' => 2]);

    $this->get(route('properties', [
        'locale' => 'en',
        'type' => 'villa',
        'price' => '250_500',
        'city' => 'Nice',
        'rooms' => '3',
    ]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $match->slug)
            ->where('filters', ['type' => 'villa', 'price' => '250_500', 'city' => 'Nice', 'rooms' => '3']));
});

test('price ranges include their lower bound and exclude their upper bound', function () {
    $lowerBound = Property::factory()->create(['price' => 250_000]);
    Property::factory()->create(['price' => 500_000]);

    $this->get(route('properties', ['locale' => 'en', 'price' => '250_500']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $lowerBound->slug));
});

test('the studio room filter matches studios and the four room filter matches four bedrooms or more', function () {
    $studio = Property::factory()->create(['type' => PropertyType::Studio, 'bedrooms' => null]);
    $large = Property::factory()->create(['type' => PropertyType::House, 'bedrooms' => 5]);
    Property::factory()->create(['type' => PropertyType::House, 'bedrooms' => 3]);

    $this->get(route('properties', ['locale' => 'en', 'rooms' => 'studio']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $studio->slug));

    $this->get(route('properties', ['locale' => 'en', 'rooms' => '4']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $large->slug));
});

test('unknown filter values are ignored', function () {
    Property::factory()->count(2)->create();

    $this->get(route('properties', ['locale' => 'en', 'type' => 'castle', 'price' => 'cheap', 'rooms' => '9']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 2)
            ->where('filters', ['type' => '', 'price' => '', 'city' => '', 'rooms' => '']));
});

test('a property page shows the property with its rendered body', function () {
    $property = Property::factory()->forRent()->create(['body' => '## Living room']);

    $this->get(route('properties.show', ['locale' => 'en', 'slug' => $property->slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PropertyShow')
            ->where('property.slug', $property->slug)
            ->where('property.offerType.value', OfferType::Rent->value)
            ->where('property.body', "<h2>Living room</h2>\n"));
});

test('an unknown property shows the missing property page', function () {
    $this->get(route('properties.show', ['locale' => 'en', 'slug' => 'unknown']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PropertyShow')
            ->where('property', null));
});

test('the home page shows the six latest properties', function () {
    Property::factory()->count(7)->create();

    $this->get(route('home', ['locale' => 'en']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('featuredProperties', 6)
            ->has('cities'));
});

test('the price is stored in cents and read in euros', function () {
    $property = Property::factory()->create(['price' => 1250.5]);

    expect($property->getRawOriginal('price'))->toBe(125050)
        ->and($property->refresh()->price)->toBe(1250.5);
});

test('the map page lists only properties with coordinates', function () {
    $located = Property::factory()->create(['latitude' => 43.7, 'longitude' => 7.26]);
    Property::factory()->create(['latitude' => null, 'longitude' => null]);

    $this->get(route('properties.map', ['locale' => 'en']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PropertiesMap')
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $located->slug)
            ->where('properties.data.0.latitude', 43.7)
            ->where('properties.data.0.longitude', 7.26)
            ->where('total', 1)
            ->where('bounds', null));
});

test('the map page only lists properties inside the visible area', function () {
    $nice = Property::factory()->create(['latitude' => 43.7, 'longitude' => 7.26]);
    Property::factory()->create(['latitude' => 48.85, 'longitude' => 2.35]);

    $this->get(route('properties.map', ['locale' => 'en', 'bounds' => '43.5,7.1,44.5,8.5']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $nice->slug)
            ->where('total', 1)
            ->where('bounds', ['south' => 43.5, 'west' => 7.1, 'north' => 44.5, 'east' => 8.5]));
});

test('the map page ignores an invalid area', function (string $bounds) {
    Property::factory()->count(2)->create();

    $this->get(route('properties.map', ['locale' => 'en', 'bounds' => $bounds]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 2)
            ->where('bounds', null));
})->with([
    'not enough coordinates' => '43,7,44',
    'not numeric' => '43,7,north,8',
    'south above north' => '44,7,43,8',
]);

test('the map page lists twenty properties per page and loads the next ones on scroll', function () {
    Property::factory()->count(21)->create(['latitude' => 43.7, 'longitude' => 7.26]);
    $oldest = Property::query()->oldest('id')->first();

    $this->get(route('properties.map', ['locale' => 'en']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 20)
            ->where('total', 21));

    $this->get(route('properties.map', ['locale' => 'en', 'page' => 2]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $oldest->slug)
            ->where('total', 21));
});

test('the map page applies the search filters within the visible area', function () {
    $match = Property::factory()->create(['type' => PropertyType::Villa, 'city' => 'Nice', 'latitude' => 43.7, 'longitude' => 7.26]);
    Property::factory()->create(['type' => PropertyType::House, 'city' => 'Nice', 'latitude' => 43.7, 'longitude' => 7.26]);
    Property::factory()->create(['type' => PropertyType::Villa, 'city' => 'Paris', 'latitude' => 48.85, 'longitude' => 2.35]);

    $this->get(route('properties.map', ['locale' => 'en', 'type' => 'villa', 'bounds' => '43.5,7.1,44.5,8.5']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', $match->slug)
            ->where('total', 1)
            ->where('filters', ['type' => 'villa', 'price' => '', 'city' => '', 'rooms' => ''])
            ->where('cities', ['Nice', 'Paris'])
            ->has('propertyTypes'));
});
