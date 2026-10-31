<?php

declare(strict_types=1);

use App\Enums\Locale;
use App\Models\User;
use Illuminate\Support\Arr;
use Inertia\Testing\AssertableInertia;

test('every public language serves the site with its own translations', function (Locale $locale) {
    $this->get(route('home', ['locale' => $locale->value]))
        ->assertOk()
        ->assertSee('<html lang="'.$locale->value.'"', escape: false)
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->where('locale', $locale->value)
            ->where('translations.nav.about', trans('front.nav.about', locale: $locale->value))
            ->has('locales', count(Locale::cases())));
})->with(Locale::cases());

test('chinese and russian have a translation for every french string', function (Locale $locale) {
    $keys = fn (array $translations): array => array_keys(Arr::dot($translations));

    expect($keys(trans('front', locale: $locale->value)))->toBe($keys(trans('front', locale: 'fr')))
        ->and(trans('front.nav.about', locale: $locale->value))->not->toBe(trans('front.nav.about', locale: 'fr'));
})->with([Locale::Chinese, Locale::Russian]);

test('an unknown language is not served', function () {
    $this->get('/de/about')->assertNotFound();
});

test('the back office stays in french', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin/properties')
        ->assertOk();

    expect(app()->getLocale())->toBe('fr');
});
