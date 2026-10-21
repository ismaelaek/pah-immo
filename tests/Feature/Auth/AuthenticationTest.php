<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Auth\Pages\Login;
use Filament\Facades\Filament;
use Livewire\Livewire;

test('login screen can be rendered', function () {
    $this->get(Filament::getLoginUrl())->assertOk();
});

test('login screen uses the brand logo and favicon', function () {
    $this->get(Filament::getLoginUrl())
        ->assertOk()
        ->assertSeeHtml('/assets/logo.png')
        ->assertSeeHtml('/assets/logo-dark.png')
        ->assertSeeHtml('/assets/favicon.png');
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'password',
        ])
        ->call('authenticate')
        ->assertHasNoFormErrors()
        ->assertRedirect(Filament::getUrl());

    $this->assertAuthenticatedAs($user);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    Livewire::test(Login::class)
        ->fillForm([
            'email' => $user->email,
            'password' => 'wrong-password',
        ])
        ->call('authenticate')
        ->assertHasFormErrors(['email']);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post(Filament::getLogoutUrl())
        ->assertRedirect(Filament::getLoginUrl());

    $this->assertGuest();
});
