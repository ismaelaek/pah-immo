<?php

declare(strict_types=1);

use App\Models\User;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;

test('guests are redirected to the login page', function () {
    $this->get(Filament::getUrl())
        ->assertRedirect(Filament::getLoginUrl());
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(Filament::getUrl())
        ->assertOk()
        ->assertSeeLivewire(Dashboard::class);
});
