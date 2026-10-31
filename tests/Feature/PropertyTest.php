<?php

declare(strict_types=1);

use App\Enums\MediaCollection;
use App\Models\Agency;
use App\Models\Property;
use App\Models\PropertyDocument;
use App\Models\PropertyPicture;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

test('a property belongs to the agency whose string apimo id matches its integer agency', function () {
    $agency = Agency::factory()->create(['apimo_id' => '101']);
    $property = Property::factory()->create(['agency' => 101]);
    Property::factory()->create(['agency' => 202]);

    expect($property->load('owningAgency')->owningAgency->is($agency))->toBeTrue()
        ->and($agency->load('properties')->properties)->toHaveCount(1)
        ->and($agency->properties->first()->is($property))->toBeTrue();
});

test('a property resolves its parent and child lots through the apimo parent id', function () {
    $parent = Property::factory()->create();
    $lot = Property::factory()->childOf($parent)->create();
    Property::factory()->create();

    expect($lot->load('parentProperty')->parentProperty->is($parent))->toBeTrue()
        ->and($parent->load('childProperties')->childProperties->modelKeys())->toBe([$lot->id]);
});

test('a document shared with a lot resolves the parent property as its owner', function () {
    $parent = Property::factory()->create();
    $lot = Property::factory()->childOf($parent)->create();

    $document = PropertyDocument::factory()->for($lot)->create(['owner' => $parent->apimo_id]);

    $document->load('property', 'ownerProperty');

    expect($document->property->is($lot))->toBeTrue()
        ->and($document->ownerProperty->is($parent))->toBeTrue();
});

test('pictures are returned in apimo rank order', function () {
    $property = Property::factory()->create();
    $second = PropertyPicture::factory()->for($property)->create(['rank' => 2]);
    $first = PropertyPicture::factory()->for($property)->create(['rank' => 1]);

    expect($property->load('pictures')->pictures->modelKeys())->toBe([$first->id, $second->id]);
});

test('deleting a property deletes its nested apimo resources', function () {
    $property = Property::factory()->create();
    PropertyPicture::factory()->for($property)->create();
    PropertyDocument::factory()->for($property)->create();

    $property->delete();

    expect(PropertyPicture::query()->count())->toBe(0)
        ->and(PropertyDocument::query()->count())->toBe(0);
});

test('the same apimo property id is allowed in different agencies', function () {
    Property::factory()->create(['agency' => 101, 'apimo_id' => 181297]);
    Property::factory()->create(['agency' => 202, 'apimo_id' => 181297]);

    expect(Property::query()->where('apimo_id', 181297)->count())->toBe(2);
});

test('the same apimo property id is rejected twice in one agency', function () {
    Property::factory()->create(['agency' => 101, 'apimo_id' => 181297]);

    Property::factory()->create(['agency' => 101, 'apimo_id' => 181297]);
})->throws(UniqueConstraintViolationException::class);

test('a picture keeps a single downloaded image with small, medium and large conversions', function () {
    Storage::fake('public');

    $picture = PropertyPicture::factory()->create();
    $picture->addMedia(UploadedFile::fake()->image('old.jpg', 1920, 1440))->toMediaCollection(MediaCollection::Images->value);
    $media = $picture->addMedia(UploadedFile::fake()->image('new.jpg', 1920, 1440))->toMediaCollection(MediaCollection::Images->value);

    expect($picture->refresh()->getMedia(MediaCollection::Images->value)->modelKeys())->toBe([$media->id])
        ->and($media->model_type)->toBe('property_picture')
        ->and($media->getGeneratedConversions()->filter()->keys()->all())->toEqualCanonicalizing(['small', 'medium', 'large']);
});

test('deleting a property removes its picture images from storage', function () {
    Storage::fake('public');

    $property = Property::factory()->create();
    $picture = PropertyPicture::factory()->for($property)->create();
    $media = $picture->addMedia(UploadedFile::fake()->image('front.jpg'))->toMediaCollection(MediaCollection::Images->value);

    $property->delete();

    expect(Media::query()->count())->toBe(0);
    Storage::disk('public')->assertMissing($media->getPathRelativeToRoot());
});
