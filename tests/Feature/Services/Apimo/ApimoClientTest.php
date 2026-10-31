<?php

declare(strict_types=1);

use App\Services\Apimo\ApimoClient;
use App\Services\Apimo\ApimoException;
use App\Services\Apimo\ApimoQuotaExceededException;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Http::preventStrayRequests();
});

test('it refuses to send more requests than the daily budget allows', function () {
    configureApimo(dailyRequestBudget: 2);
    Http::fake(['api.apimo.pro/*' => Http::response(['agencies' => []])]);
    $client = app(ApimoClient::class);

    $client->agencies();
    $client->agencies();

    expect(fn () => $client->agencies())->toThrow(ApimoQuotaExceededException::class);
    Http::assertSentCount(2);
});

test('the daily budget starts over on the next paris day', function () {
    configureApimo(dailyRequestBudget: 1);
    Http::fake(['api.apimo.pro/*' => Http::response(['agencies' => []])]);
    $client = app(ApimoClient::class);
    $this->travelTo(now(ApimoClient::QUOTA_TIMEZONE)->setTime(23, 50));
    $client->agencies();

    $this->travelTo(now(ApimoClient::QUOTA_TIMEZONE)->addDay()->setTime(0, 10));
    $client->agencies();

    Http::assertSentCount(2);
});

test('after apimo answers 429 it stops calling apimo until the quota resets', function () {
    configureApimo();
    Http::fake(['api.apimo.pro/*' => Http::response(['status' => 429, 'title' => 'Too Many Requests', 'detail' => 'Please limit your daily request to the server'], 429)]);
    $client = app(ApimoClient::class);

    expect(fn () => $client->agencies())->toThrow(ApimoQuotaExceededException::class)
        ->and(fn () => $client->agencies())->toThrow(ApimoQuotaExceededException::class);

    Http::assertSentCount(1);
});

test('an error body returned with http 200 is treated as an error', function () {
    configureApimo();
    Http::fake(['api.apimo.pro/*' => Http::response(['status' => 403, 'title' => 'Forbidden', 'detail' => 'Property not found.'])]);

    app(ApimoClient::class)->agencies();
})->throws(ApimoException::class, 'Property not found.');

test('it sends nothing when the credentials are missing', function () {
    config(['services.apimo.provider_id' => null, 'services.apimo.token' => null]);
    Http::fake();

    expect(fn () => app(ApimoClient::class)->agencies())->toThrow(ApimoException::class, 'APIMO_PROVIDER_ID');
    Http::assertNothingSent();
});
