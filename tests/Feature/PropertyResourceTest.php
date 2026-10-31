<?php

declare(strict_types=1);

use App\Filament\Resources\Properties\Pages\ListProperties;
use App\Filament\Resources\Properties\Pages\ViewProperty;
use App\Filament\Resources\Properties\PropertyResource;
use App\Filament\Resources\Properties\RelationManagers\AreasRelationManager;
use App\Filament\Resources\Properties\RelationManagers\DocumentsRelationManager;
use App\Filament\Resources\Properties\RelationManagers\MediasRelationManager;
use App\Filament\Resources\Properties\RelationManagers\RegulationsRelationManager;
use App\Models\Agency;
use App\Models\CatalogEntry;
use App\Models\Property;
use App\Models\PropertyArea;
use App\Models\PropertyComment;
use App\Models\PropertyDocument;
use App\Models\PropertyMedia;
use App\Models\PropertyRegulation;
use App\Models\User;
use Filament\Actions\Testing\TestAction;
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

test('the list shows apimo catalog labels and filters by property type', function () {
    CatalogEntry::factory()->create(['catalog' => 'property_type', 'apimo_id' => 2, 'culture' => 'fr_FR', 'name' => 'Maison']);
    $house = Property::factory()->create(['type' => 2]);
    $apartment = Property::factory()->create(['type' => 1]);

    Livewire::test(ListProperties::class)
        ->assertTableColumnFormattedStateSet('type', 'Maison', $house)
        ->filterTable('type', ['2'])
        ->assertCanSeeTableRecords([$house])
        ->assertCanNotSeeTableRecords([$apartment]);
});

test('properties can be searched by reference', function () {
    $match = Property::factory()->create(['reference' => 'PAH-00042']);
    $other = Property::factory()->create(['reference' => 'PAH-00099']);

    Livewire::test(ListProperties::class)
        ->searchTable('00042')
        ->assertCanSeeTableRecords([$match])
        ->assertCanNotSeeTableRecords([$other]);
});

test('the property page shows the apimo details', function () {
    Agency::factory()->create(['apimo_id' => '101', 'name' => 'Agence Riviera']);
    $property = Property::factory()->create(['agency' => 101]);
    PropertyComment::factory()->for($property)->create(['language' => 'fr', 'title' => 'Villa Horizon']);
    PropertyArea::factory()->for($property)->create(['type' => 8, 'number' => 2]);

    Livewire::test(ViewProperty::class, ['record' => $property->id])
        ->assertOk()
        ->assertSee('Villa Horizon')
        ->assertSee('Agence Riviera')
        ->assertSchemaStateSet(['bathrooms' => 2])
        ->assertActionHasUrl(TestAction::make('viewOnWebsite'), route('properties.show', ['locale' => 'fr', 'slug' => (string) $property->id]));
});

test('properties cannot be created, edited or deleted from the admin panel', function () {
    $property = Property::factory()->create();

    expect(PropertyResource::canCreate())->toBeFalse()
        ->and(PropertyResource::canEdit($property))->toBeFalse()
        ->and(PropertyResource::canDelete($property))->toBeFalse()
        ->and(PropertyResource::canDeleteAny())->toBeFalse()
        ->and(array_keys(PropertyResource::getPages()))->toBe(['index', 'view']);
});

test('the property page lists its rooms, diagnostics, documents and videos', function (string $relationManager, string $factory, array $attributes, string $expected) {
    CatalogEntry::factory()->create(['catalog' => 'property_areas', 'apimo_id' => 8, 'culture' => 'fr_FR', 'name' => 'Salle de bains']);
    CatalogEntry::factory()->create(['catalog' => 'property_regulation', 'apimo_id' => 1, 'culture' => 'fr_FR', 'name' => 'DPE']);
    $property = Property::factory()->create();
    $factory::factory()->for($property)->create($attributes);

    Livewire::test($relationManager, ['ownerRecord' => $property, 'pageClass' => ViewProperty::class])
        ->assertOk()
        ->assertSee($expected);
})->with([
    'rooms' => [AreasRelationManager::class, PropertyArea::class, ['type' => 8], 'Salle de bains'],
    'diagnostics' => [RegulationsRelationManager::class, PropertyRegulation::class, ['type' => 1, 'label' => 'D'], 'DPE'],
    'documents' => [DocumentsRelationManager::class, PropertyDocument::class, ['name' => 'diagnostics.pdf'], 'diagnostics.pdf'],
    'videos' => [MediasRelationManager::class, PropertyMedia::class, ['type' => 'Matterport'], 'Matterport'],
]);
