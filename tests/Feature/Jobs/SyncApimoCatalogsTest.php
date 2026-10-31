<?php

declare(strict_types=1);

use App\Jobs\SyncApimoCatalogs;
use App\Models\CatalogEntry;
use App\Services\Apimo\ApimoQuotaExceededException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    configureApimo();
    Http::preventStrayRequests();
});

test('it replaces each catalog with its values in every language', function () {
    CatalogEntry::factory()->create(['catalog' => 'property_type', 'apimo_id' => 99, 'culture' => 'fr_FR', 'name' => 'Obsolète']);
    Http::fake([
        'api.apimo.pro/catalogs/property_type*' => Http::response([
            ['id' => 1, 'culture' => 'fr_FR', 'name' => 'Appartement'],
            ['id' => 1, 'culture' => 'en_GB', 'name' => 'Apartment', 'name_plurial' => 'Apartments'],
        ]),
        'api.apimo.pro/catalogs/*' => Http::response([]),
    ]);

    SyncApimoCatalogs::dispatchSync();

    expect(CatalogEntry::query()->where('catalog', 'property_type')->orderBy('culture')->get(['apimo_id', 'culture', 'name', 'name_plurial'])->toArray())->toBe([
        ['apimo_id' => 1, 'culture' => 'en_GB', 'name' => 'Apartment', 'name_plurial' => 'Apartments'],
        ['apimo_id' => 1, 'culture' => 'fr_FR', 'name' => 'Appartement', 'name_plurial' => null],
    ]);
    Http::assertSentCount(count(SyncApimoCatalogs::CATALOGS));
    Http::assertSent(fn (Request $request): bool => $request['culture'] === 'all');
});

test('a failing catalog is skipped but an exhausted quota stops the sync', function () {
    Http::fake([
        'api.apimo.pro/catalogs/construction_step*' => Http::response(['status' => 404, 'title' => 'Not Found', 'detail' => 'Unknown catalog'], 404),
        'api.apimo.pro/catalogs/document_type*' => Http::response([]),
        'api.apimo.pro/catalogs/*' => Http::response(['status' => 429, 'title' => 'Too Many Requests', 'detail' => 'Quota'], 429),
    ]);

    expect(fn () => SyncApimoCatalogs::dispatchSync())->toThrow(ApimoQuotaExceededException::class);
    Http::assertSentCount(3);
});
