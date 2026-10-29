<?php

declare(strict_types=1);

use App\Enums\Equipment;
use App\Enums\MediaCollection;
use App\Enums\OfferType;
use App\Enums\PropertyType;
use App\Filament\Resources\Properties\Pages\CreateProperty;
use App\Filament\Resources\Properties\Pages\EditProperty;
use App\Filament\Resources\Properties\Pages\ListProperties;
use App\Http\Resources\PropertyResource;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('properties are listed in the admin panel', function () {
    $properties = Property::factory()->count(2)->create();

    Livewire::test(ListProperties::class)
        ->assertOk()
        ->assertCanSeeTableRecords($properties);
});

test('a property can be created with equipment', function () {
    Livewire::test(CreateProperty::class)
        ->fillForm([
            'title' => 'Villa Horizon',
            'reference' => 'PAH-00001',
            'description' => 'A bright villa with sea view.',
            'body' => 'Lovely villa.',
            'offer_type' => OfferType::Sale,
            'type' => PropertyType::Villa,
            'price' => 785000,
            'area' => 220,
            'bedrooms' => 5,
            'bathrooms' => null,
            'city' => 'Nice',
            'equipment' => [Equipment::SwimmingPool->value, Equipment::Garden->value],
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $property = Property::query()->sole();

    expect($property)
        ->slug->toBe('villa-horizon')
        ->offer_type->toBe(OfferType::Sale)
        ->type->toBe(PropertyType::Villa)
        ->bathrooms->toBeNull()
        ->and($property->equipment->all())->toBe([Equipment::SwimmingPool, Equipment::Garden]);
});

test('the slug is filled from the title when creating a property', function () {
    Livewire::test(CreateProperty::class)
        ->fillForm(['title' => 'Villa Côte Sud'])
        ->assertSchemaStateSet(['slug' => 'villa-cote-sud']);
});

test('the slug is kept when the title changes on an existing property', function () {
    $property = Property::factory()->create(['title' => 'Villa Horizon']);

    Livewire::test(EditProperty::class, ['record' => $property->slug])
        ->fillForm(['title' => 'Villa Horizon Renovated'])
        ->assertSchemaStateSet(['slug' => 'villa-horizon']);
});

test('a property reference must be unique', function () {
    Property::factory()->create(['reference' => 'PAH-00001']);

    Livewire::test(CreateProperty::class)
        ->fillForm([
            'reference' => 'PAH-00001',
        ])
        ->call('create')
        ->assertHasFormErrors(['reference' => 'unique']);
});

test('a property can keep its own reference when edited', function () {
    $property = Property::factory()->create(['reference' => 'PAH-00001']);

    Livewire::test(EditProperty::class, ['record' => $property->slug])
        ->fillForm(['title' => 'Updated title'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($property->refresh()->title)->toBe('Updated title');
});

test('property images generate small, medium and large conversions', function () {
    Storage::fake('public');

    $property = Property::factory()->create();
    $media = $property->addMedia(UploadedFile::fake()->image('front.jpg', 2000, 1500))
        ->toMediaCollection(MediaCollection::Images->value);

    $resource = (new PropertyResource($property->refresh()))->resolve();

    expect($media->refresh()->getGeneratedConversions()->filter()->keys()->all())
        ->toEqualCanonicalizing(['small', 'medium', 'large'])
        ->and($resource['images'])->toHaveCount(1)
        ->and($resource['images'][0]['large'])->toContain('conversions/');
});
