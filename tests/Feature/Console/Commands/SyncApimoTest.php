<?php

declare(strict_types=1);

use App\Jobs\DownloadPropertyPicture;
use App\Models\Property;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    configureApimo();
    Http::preventStrayRequests();
    Queue::fake([DownloadPropertyPicture::class]);
});

test('it syncs agencies then properties and reports the apimo requests used', function () {
    Http::fake([
        'api.apimo.pro/agencies' => Http::response(['total_items' => 1, 'timestamp' => null, 'agencies' => [apimoFixture('agency')]]),
        'api.apimo.pro/agencies/101/properties*' => Http::response(['total_items' => 1, 'timestamp' => 1718460000, 'properties' => [apimoFixture('property')]]),
    ]);

    $this->artisan('apimo:sync')
        ->expectsOutputToContain('Apimo requests used: 2 this run, 2 today.')
        ->assertSuccessful();

    expect(Property::query()->sole()->apimo_id)->toBe(181297);
});

test('it fails with the apimo error when the quota is exhausted', function () {
    Http::fake(['api.apimo.pro/*' => Http::response(['status' => 429, 'title' => 'Too Many Requests', 'detail' => 'Quota'], 429)]);

    $this->artisan('apimo:sync')
        ->expectsOutputToContain('quota is exhausted')
        ->assertFailed();
});
