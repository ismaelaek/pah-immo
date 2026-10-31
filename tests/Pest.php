<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/**
 * Load an Apimo response fixture from tests/Fixtures/apimo, with top-level overrides.
 *
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function apimoFixture(string $name, array $overrides = []): array
{
    $fixture = json_decode((string) file_get_contents(__DIR__."/Fixtures/apimo/{$name}.json"), true, flags: JSON_THROW_ON_ERROR);

    return array_replace($fixture, $overrides);
}

function configureApimo(int $dailyRequestBudget = 900): void
{
    config([
        'services.apimo.provider_id' => '1234',
        'services.apimo.token' => 'secret-token',
        'services.apimo.base_url' => 'https://api.apimo.pro',
        'services.apimo.daily_request_budget' => $dailyRequestBudget,
    ]);
}
