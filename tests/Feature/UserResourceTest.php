<?php

declare(strict_types=1);

use App\Enums\MediaCollection;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\UserResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('users are listed in the admin panel', function () {
    $users = User::factory()->count(2)->create();

    Livewire::test(ListUsers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($users);
});

test('a user can be created with a confirmed password', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::query()->where('email', 'jane@example.com')->sole();

    expect($user->name)->toBe('Jane Doe')
        ->and(Hash::check('secret-password', $user->password))->toBeTrue();
});

test('a user can be created with an avatar', function () {
    Storage::fake('public');

    Livewire::test(CreateUser::class)
        ->fillForm([
            'avatar' => UploadedFile::fake()->image('avatar.jpg', 400, 400),
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'secret-password',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $user = User::query()->where('email', 'jane@example.com')->sole();

    expect($user->getFirstMedia(MediaCollection::Avatar->value))->not->toBeNull();
});

test('a user cannot be created when the password confirmation does not match', function () {
    Livewire::test(CreateUser::class)
        ->fillForm([
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'password' => 'secret-password',
            'password_confirmation' => 'other-password',
        ])
        ->call('create')
        ->assertHasFormErrors(['password_confirmation' => 'same']);

    expect(User::query()->where('email', 'jane@example.com')->exists())->toBeFalse();
});

test('the edit form hides the password fields and keeps the password', function () {
    $user = User::factory()->create(['password' => 'original-password']);

    Livewire::test(EditUser::class, ['record' => $user->getRouteKey()])
        ->assertFormFieldHidden('password')
        ->assertFormFieldHidden('password_confirmation')
        ->assertActionDoesNotExist('delete')
        ->fillForm(['name' => 'Renamed'])
        ->call('save')
        ->assertHasNoFormErrors();

    $user->refresh();

    expect($user->name)->toBe('Renamed')
        ->and(Hash::check('original-password', $user->password))->toBeTrue();
});

test('users cannot be deleted', function () {
    expect(UserResource::canDelete(User::factory()->create()))->toBeFalse()
        ->and(UserResource::canDeleteAny())->toBeFalse();
});
