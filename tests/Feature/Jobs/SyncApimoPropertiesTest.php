<?php

declare(strict_types=1);

use App\Enums\MediaCollection;
use App\Jobs\DownloadPropertyPicture;
use App\Jobs\SyncApimoProperties;
use App\Models\Agency;
use App\Models\Property;
use App\Models\PropertyPicture;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

beforeEach(function () {
    configureApimo();
    Queue::fake([DownloadPropertyPicture::class]);
    Http::preventStrayRequests();
    Agency::factory()->create(['apimo_id' => '101']);
});

/**
 * @param  list<array<string, mixed>>  $properties
 * @return array<string, mixed>
 */
function propertiesPage(array $properties, ?int $totalItems = null, ?int $timestamp = 1718460000): array
{
    return ['total_items' => $totalItems ?? count($properties), 'timestamp' => $timestamp, 'processing_time' => 0.1, 'properties' => $properties];
}

test('it imports a property with its nested apimo resources and queues its picture download', function () {
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([apimoFixture('property')]))]);

    SyncApimoProperties::dispatchSync();

    $property = Property::query()->with(['pictures', 'medias', 'documents', 'comments', 'areas', 'regulations', 'services', 'proximities'])->sole();

    expect($property)
        ->apimo_id->toBe(181297)
        ->agency->toBe(101)
        ->reference->toBe('24367')
        ->rooms->toBe(2.5)
        ->floor_value->toBe('last')
        ->price_value->toBe(250000.0)
        ->city_name->toBe('Nice')
        ->view_landscape->toBe(['1', '4'])
        ->boat_length->toBeNull()
        ->and($property->services->map->only('type', 'comments')->all())->toBe([
            ['type' => 12, 'comments' => null],
            ['type' => 30, 'comments' => [['language' => 'fr', 'comment' => 'Piscine chauffée']]],
        ])
        ->and($property->proximities->map->only('type', 'length', 'length_type')->all())->toBe([
            ['type' => 7, 'length' => null, 'length_type' => null],
            ['type' => 2, 'length' => 300, 'length_type' => '1'],
        ])
        ->and($property->pictures->sole()->apimo_id)->toBe(66321497)
        ->and($property->medias->sole()->apimo_id)->toBe('123456')
        ->and($property->documents->sole()->owner)->toBe(181297)
        ->and($property->comments->sole()->title)->toBe('2P CENTRE NICE')
        ->and($property->areas->sole()->beds_double)->toBe(1)
        ->and($property->regulations->sole()->label)->toBe('D');

    Queue::assertPushed(DownloadPropertyPicture::class, fn (DownloadPropertyPicture $job): bool => $job->picture->is($property->pictures->sole()));
});

test('it requests every active agency in one call with the maximum page size and advanced details', function () {
    Agency::factory()->create(['apimo_id' => '102']);
    Agency::factory()->create(['apimo_id' => '103', 'active' => false]);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([]))]);

    SyncApimoProperties::dispatchSync();

    Http::assertSent(fn (Request $request): bool => str_starts_with($request->url(), 'https://api.apimo.pro/agencies/101_102/properties?')
        && $request['limit'] === 1000
        && $request['with'] === ['advanced']
        && $request->hasHeader('Authorization', 'Basic '.base64_encode('1234:secret-token')));
});

test('an incremental sync only asks for changes since the stored timestamp and stores the new one', function () {
    Cache::forever(SyncApimoProperties::TIMESTAMP_CACHE_KEY, 1718000000);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([apimoFixture('property')], timestamp: 1718460000))]);

    SyncApimoProperties::dispatchSync();

    Http::assertSent(fn (Request $request): bool => $request['timestamp'] === 1718000000);
    expect(Cache::get(SyncApimoProperties::TIMESTAMP_CACHE_KEY))->toBe(1718460000);
});

test('a sync with no changes keeps the stored timestamp', function () {
    Cache::forever(SyncApimoProperties::TIMESTAMP_CACHE_KEY, 1718000000);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([], timestamp: null))]);

    SyncApimoProperties::dispatchSync();

    expect(Cache::get(SyncApimoProperties::TIMESTAMP_CACHE_KEY))->toBe(1718000000);
});

test('it fetches the next page only while apimo reports more properties', function () {
    Http::fake(['api.apimo.pro/*' => Http::sequence()
        ->push(propertiesPage([apimoFixture('property', ['id' => 1])], totalItems: 2))
        ->push(propertiesPage([apimoFixture('property', ['id' => 2])], totalItems: 2))]);

    SyncApimoProperties::dispatchSync();

    Http::assertSentCount(2);
    Http::assertSent(fn (Request $request): bool => $request['offset'] === 1);
    expect(Property::query()->pluck('apimo_id')->all())->toEqualCanonicalizing([1, 2]);
});

test('a full sync ignores the stored timestamp and deletes properties apimo no longer returns for synced agencies', function () {
    Cache::forever(SyncApimoProperties::TIMESTAMP_CACHE_KEY, 1718000000);
    $sold = Property::factory()->create(['agency' => 101, 'apimo_id' => 555]);
    $otherAgency = Property::factory()->create(['agency' => 999, 'apimo_id' => 555]);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([apimoFixture('property')]))]);

    SyncApimoProperties::dispatchSync(full: true);

    Http::assertSent(fn (Request $request): bool => ! isset($request['timestamp']));
    expect(Property::query()->find($sold->id))->toBeNull()
        ->and(Property::query()->find($otherAgency->id))->not->toBeNull()
        ->and(Property::query()->where('apimo_id', 181297)->exists())->toBeTrue();
});

test('an incremental sync never deletes properties missing from the response', function () {
    $unchanged = Property::factory()->create(['agency' => 101]);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([apimoFixture('property')]))]);

    SyncApimoProperties::dispatchSync();

    expect(Property::query()->find($unchanged->id))->not->toBeNull();
});

test('a re-import removes pictures apimo dropped and does not download already stored images again', function () {
    Storage::fake('public');
    $property = Property::factory()->create(['agency' => 101, 'apimo_id' => 181297]);
    $dropped = PropertyPicture::factory()->for($property)->create(['apimo_id' => 1]);
    $dropped->addMedia(UploadedFile::fake()->image('dropped.jpg'))->toMediaCollection(MediaCollection::Images->value);
    $kept = PropertyPicture::factory()->for($property)->create(['apimo_id' => 66321497, 'url' => 'https://d1.apimo.pro/pictures/66321497.jpg']);
    $kept->addMedia(UploadedFile::fake()->image('kept.jpg'))
        ->withCustomProperties(['source_url' => $kept->url])
        ->toMediaCollection(MediaCollection::Images->value);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([apimoFixture('property')]))]);

    SyncApimoProperties::dispatchSync();

    expect(PropertyPicture::query()->pluck('id')->all())->toBe([$kept->id])
        ->and(Media::query()->pluck('model_id')->all())->toBe([$kept->id]);
    Queue::assertNotPushed(DownloadPropertyPicture::class);
});

test('a picture whose apimo url changed is downloaded again', function () {
    Storage::fake('public');
    $property = Property::factory()->create(['agency' => 101, 'apimo_id' => 181297]);
    $picture = PropertyPicture::factory()->for($property)->create(['apimo_id' => 66321497]);
    $picture->addMedia(UploadedFile::fake()->image('old.jpg'))
        ->withCustomProperties(['source_url' => 'https://d1.apimo.pro/pictures/old.jpg'])
        ->toMediaCollection(MediaCollection::Images->value);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([apimoFixture('property')]))]);

    SyncApimoProperties::dispatchSync();

    Queue::assertPushed(DownloadPropertyPicture::class, fn (DownloadPropertyPicture $job): bool => $job->picture->is($picture));
});

test('one invalid property is logged and does not stop the others', function () {
    Log::spy();
    $invalid = apimoFixture('property', ['id' => 1]);
    unset($invalid['price']);
    Http::fake(['api.apimo.pro/*' => Http::response(propertiesPage([$invalid, apimoFixture('property', ['id' => 2])]))]);

    SyncApimoProperties::dispatchSync();

    expect(Property::query()->pluck('apimo_id')->all())->toBe([2]);
    Log::shouldHaveReceived('error')->once()->withArgs(fn (string $message, array $context): bool => $context['apimo_id'] === 1);
});

test('it sends no request when no agency is active', function () {
    Agency::query()->update(['active' => false]);
    Http::fake();

    SyncApimoProperties::dispatchSync();

    Http::assertNothingSent();
});
