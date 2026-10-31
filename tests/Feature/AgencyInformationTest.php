<?php

declare(strict_types=1);

use App\Filament\Pages\AgencyInformation;
use App\Models\Agency;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('the settings page shows the active agency synced from apimo', function () {
    Agency::factory()->create(['name' => 'Ancienne agence', 'active' => false]);
    Agency::factory()->create([
        'name' => 'Agence Riviera',
        'email' => 'contact@riviera.test',
        'stories' => [['language' => 'fr', 'title' => 'Qui sommes-nous', 'content' => 'Une agence niçoise.']],
    ]);

    Livewire::test(AgencyInformation::class)
        ->assertOk()
        ->assertSee('Agence Riviera')
        ->assertSee('contact@riviera.test')
        ->assertSee('Une agence niçoise.')
        ->assertDontSee('Ancienne agence');
});

test('the settings page explains that the agency appears after the first sync', function () {
    Livewire::test(AgencyInformation::class)
        ->assertOk()
        ->assertSee(__('The agency information appears here after the first Apimo sync.'));
});

test('the settings page is in the settings navigation group', function () {
    $this->get(AgencyInformation::getUrl())
        ->assertOk()
        ->assertSee(__('Agency information'));
});
