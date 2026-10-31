<?php

declare(strict_types=1);

use App\Enums\OfferType;
use App\Models\CatalogEntry;
use App\Models\Property;
use App\Models\PropertyArea;
use App\Models\PropertyComment;
use App\Models\PropertyPicture;
use App\Models\PropertyService;
use Inertia\Testing\AssertableInertia;

/**
 * Matches a serialized property against a model through the local id leading its slug.
 */
function isSlugOf(Property $property): Closure
{
    return fn (string $slug): bool => Property::idFromRouteSlug($slug) === $property->id;
}

test('the properties page lists properties with the available filters', function () {
    CatalogEntry::factory()->create(['catalog' => 'property_type', 'apimo_id' => 1, 'culture' => 'en_GB', 'name' => 'Apartment']);
    CatalogEntry::factory()->create(['catalog' => 'property_type', 'apimo_id' => 1, 'culture' => 'fr_FR', 'name' => 'Appartement']);
    Property::factory()->create(['city_name' => 'Nice']);
    Property::factory()->create(['city_name' => 'Lyon']);

    $this->get(route('properties', ['locale' => 'en']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Properties')
            ->has('properties.data', 2)
            ->where('properties.total', 2)
            ->where('cities', ['Lyon', 'Nice'])
            ->where('propertyTypes', [['value' => '1', 'label' => 'Apartment']])
            ->where('filters', ['type' => '', 'price' => '', 'city' => '', 'rooms' => '']));
});

test('properties can be filtered by type, price, city and rooms', function () {
    $match = Property::factory()->create(['type' => 2, 'price_value' => 300_000, 'city_name' => 'Nice', 'bedrooms' => 3]);
    Property::factory()->create(['type' => 1, 'price_value' => 300_000, 'city_name' => 'Nice', 'bedrooms' => 3]);
    Property::factory()->create(['type' => 2, 'price_value' => 600_000, 'city_name' => 'Nice', 'bedrooms' => 3]);
    Property::factory()->create(['type' => 2, 'price_value' => 300_000, 'city_name' => 'Lyon', 'bedrooms' => 3]);
    Property::factory()->create(['type' => 2, 'price_value' => 300_000, 'city_name' => 'Nice', 'bedrooms' => 2]);

    $this->get(route('properties', [
        'locale' => 'en',
        'type' => '2',
        'price' => '250_500',
        'city' => 'Nice',
        'rooms' => '3',
    ]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($match))
            ->where('filters', ['type' => '2', 'price' => '250_500', 'city' => 'Nice', 'rooms' => '3']));
});

test('price ranges include their lower bound, exclude their upper bound and skip hidden prices', function () {
    $lowerBound = Property::factory()->create(['price_value' => 250_000]);
    Property::factory()->create(['price_value' => 500_000]);
    Property::factory()->create(['price_value' => 300_000, 'price_hide' => true]);

    $this->get(route('properties', ['locale' => 'en', 'price' => '250_500']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($lowerBound)));
});

test('the studio room filter matches single-room properties and the four room filter matches four bedrooms or more', function () {
    $studio = Property::factory()->create(['rooms' => 1, 'bedrooms' => 0]);
    $large = Property::factory()->create(['rooms' => 6, 'bedrooms' => 5]);
    Property::factory()->create(['rooms' => 4, 'bedrooms' => 3]);

    $this->get(route('properties', ['locale' => 'en', 'rooms' => 'studio']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($studio)));

    $this->get(route('properties', ['locale' => 'en', 'rooms' => '4']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($large)));
});

test('unknown filter values are ignored', function () {
    Property::factory()->count(2)->create();

    $this->get(route('properties', ['locale' => 'en', 'type' => 'castle', 'price' => 'cheap', 'rooms' => '9']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 2)
            ->where('filters', ['type' => '', 'price' => '', 'city' => '', 'rooms' => '']));
});

test('a property page shows the apimo property in the visitor language', function () {
    CatalogEntry::factory()->create(['catalog' => 'property_type', 'apimo_id' => 2, 'culture' => 'en_GB', 'name' => 'House']);
    CatalogEntry::factory()->create(['catalog' => 'property_period', 'apimo_id' => 4, 'culture' => 'en_GB', 'name' => 'Month']);
    CatalogEntry::factory()->create(['catalog' => 'property_service', 'apimo_id' => 30, 'culture' => 'en_GB', 'name' => 'Swimming pool']);
    $property = Property::factory()->create([
        'type' => 2,
        'category' => 2,
        'price_value' => 2400,
        'price_period' => 4,
        'area_value' => 120.5,
        'bedrooms' => 3,
        'city_name' => 'Nice',
        'district_name' => 'Cimiez',
    ]);
    PropertyComment::factory()->for($property)->create([
        'language' => 'en',
        'title' => 'Family house',
        'hook' => 'Quiet and bright.',
        'comment' => "Large living room.\nSea view.\n\n<b>Garden</b> & pool.",
        'comment_full' => null,
    ]);
    PropertyArea::factory()->for($property)->create(['type' => 8, 'number' => 2]);
    PropertyArea::factory()->for($property)->create(['type' => 13, 'number' => 1]);
    PropertyArea::factory()->for($property)->create(['type' => 1, 'number' => 3]);
    PropertyService::factory()->for($property)->create(['type' => 30]);
    PropertyPicture::factory()->for($property)->create(['rank' => 1, 'internet' => '1', 'url' => 'https://d1.apimo.pro/pictures/1.jpg']);
    PropertyPicture::factory()->for($property)->create(['rank' => 2, 'internet' => '0']);

    $this->get(route('properties.show', ['locale' => 'en', 'slug' => "{$property->id}-family-house"]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PropertyShow')
            ->where('property.title', 'Family house')
            ->where('property.description', 'Quiet and bright.')
            ->where('property.body', "<p>Large living room.<br>\nSea view.</p>\n<p>&lt;b&gt;Garden&lt;/b&gt; &amp; pool.</p>")
            ->where('property.type', ['value' => '2', 'label' => 'House'])
            ->where('property.offerType.value', OfferType::Rent->value)
            ->where('property.price', 2400)
            ->where('property.pricePeriod', 'Month')
            ->where('property.area', 120.5)
            ->where('property.bedrooms', 3)
            ->where('property.bathrooms', 3)
            ->where('property.neighborhood', 'Cimiez')
            ->where('property.city', 'Nice')
            ->where('property.equipment', [['value' => '30', 'label' => 'Swimming pool']])
            ->has('property.images', 1)
            ->where('property.images.0.large', 'https://d1.apimo.pro/pictures/1.jpg'));
});

test('a property page falls back to the french description, then to the type and city', function () {
    CatalogEntry::factory()->create(['catalog' => 'property_type', 'apimo_id' => 1, 'culture' => 'fr_FR', 'name' => 'Appartement']);
    $described = Property::factory()->create();
    PropertyComment::factory()->for($described)->create(['language' => 'fr', 'title' => 'Villa Horizon']);
    $undescribed = Property::factory()->create(['type' => 1, 'city_name' => 'Nice']);

    $this->get(route('properties.show', ['locale' => 'it', 'slug' => "{$described->id}-villa-horizon"]))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('property.title', 'Villa Horizon'));

    $this->get(route('properties.show', ['locale' => 'it', 'slug' => "{$undescribed->id}-appartement-nice"]))
        ->assertInertia(fn (AssertableInertia $page) => $page->where('property.title', 'Appartement Nice'));
});

test('a hidden price is not shown', function () {
    $property = Property::factory()->create(['price_value' => 950_000, 'price_hide' => true]);

    $this->get(route('properties', ['locale' => 'en']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('properties.data.0.slug', isSlugOf($property))
            ->where('properties.data.0.price', null));
});

test('an outdated property slug redirects permanently to the current one', function () {
    $property = Property::factory()->create();
    PropertyComment::factory()->for($property)->create(['language' => 'en', 'title' => 'New title']);

    $this->get(route('properties.show', ['locale' => 'en', 'slug' => "{$property->id}-old-title"]))
        ->assertRedirect(route('properties.show', ['locale' => 'en', 'slug' => "{$property->id}-new-title"]))
        ->assertStatus(301);
});

test('an unknown property shows the missing property page', function (string $slug) {
    $this->get(route('properties.show', ['locale' => 'en', 'slug' => $slug]))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PropertyShow')
            ->where('property', null));
})->with([
    'no id' => 'unknown',
    'unknown id' => '999-villa',
]);

test('the home page shows the six latest properties', function () {
    Property::factory()->count(7)->create();

    $this->get(route('home', ['locale' => 'en']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('Home')
            ->has('featuredProperties', 6)
            ->has('cities')
            ->has('propertyTypes'));
});

test('the map page lists properties with their public coordinates', function () {
    $located = Property::factory()->create(['latitude' => 43.7, 'longitude' => 7.26]);

    $this->get(route('properties.map', ['locale' => 'en']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('PropertiesMap')
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($located))
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
            ->where('properties.data.0.slug', isSlugOf($nice))
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
    Property::factory()->count(21)->create(['latitude' => 43.7, 'longitude' => 7.26, 'created_at' => '2024-06-15 14:30:00']);
    $oldest = Property::query()->oldest('id')->first();

    $this->get(route('properties.map', ['locale' => 'en']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 20)
            ->where('total', 21));

    $this->get(route('properties.map', ['locale' => 'en', 'page' => 2]))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($oldest))
            ->where('total', 21));
});

test('the map page applies the search filters within the visible area', function () {
    $match = Property::factory()->create(['type' => 2, 'city_name' => 'Nice', 'latitude' => 43.7, 'longitude' => 7.26]);
    Property::factory()->create(['type' => 1, 'city_name' => 'Nice', 'latitude' => 43.7, 'longitude' => 7.26]);
    Property::factory()->create(['type' => 2, 'city_name' => 'Paris', 'latitude' => 48.85, 'longitude' => 2.35]);

    $this->get(route('properties.map', ['locale' => 'en', 'type' => '2', 'bounds' => '43.5,7.1,44.5,8.5']))
        ->assertOk()
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($match))
            ->where('total', 1)
            ->where('filters', ['type' => '2', 'price' => '', 'city' => '', 'rooms' => ''])
            ->where('cities', ['Nice', 'Paris'])
            ->has('propertyTypes'));
});

test('the search filters are kept in the session and restored when none are given', function () {
    $house = Property::factory()->create(['type' => 2]);
    Property::factory()->create(['type' => 1]);

    $this->get(route('properties', ['locale' => 'en', 'type' => '2']))
        ->assertSessionHas('property_filters', ['type' => '2']);

    $this->get(route('properties', ['locale' => 'en']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('properties.data.0.slug', isSlugOf($house))
            ->where('filters.type', '2'));

    $this->get(route('properties.map', ['locale' => 'en']))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 1)
            ->where('filters.type', '2'));
});

test('resetting the search clears the filters kept in the session', function () {
    Property::factory()->create(['type' => 2]);
    Property::factory()->create(['type' => 1]);

    $this->withSession(['property_filters' => ['type' => '2']])
        ->get(route('properties', ['locale' => 'en', 'reset' => 1]))
        ->assertSessionMissing('property_filters')
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('properties.data', 2)
            ->where('filters', ['type' => '', 'price' => '', 'city' => '', 'rooms' => '']));
});
