<?php

declare(strict_types=1);

use App\Jobs\SyncApimoAgencies;
use App\Models\Agency;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    configureApimo();
    Http::preventStrayRequests();
});

test('it imports the connected agencies and deactivates the ones apimo no longer returns', function () {
    $disconnected = Agency::factory()->create(['apimo_id' => '999', 'active' => true]);
    Http::fake(['api.apimo.pro/agencies' => Http::response(['total_items' => 1, 'timestamp' => null, 'agencies' => [apimoFixture('agency')]])]);

    SyncApimoAgencies::dispatchSync();

    expect(Agency::query()->where('apimo_id', '101')->sole())
        ->name->toBe('Agence Riviera Immobilier')
        ->active->toBeTrue()
        ->company_name->toBe('Riviera SAS')
        ->city_id->toBe('2123')
        ->latitude->toBe('43.69821')
        ->and($disconnected->refresh()->active)->toBeFalse();
    Http::assertSentCount(1);
});
