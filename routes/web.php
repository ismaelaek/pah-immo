<?php

declare(strict_types=1);

use App\Enums\Locale;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home', ['locale' => Locale::default()->value]);
});

Route::prefix('{locale}')
    ->whereIn('locale', Locale::values())
    ->group(function (): void {
        Route::get('/', HomeController::class)->name('home');
        Route::inertia('/about', 'About')->name('about');
        Route::inertia('/properties', 'Properties')->name('properties');
        Route::get('/blog', [BlogController::class, 'index'])->name('blog');
        Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
        Route::post('/blog/{slug}/view', [BlogController::class, 'recordView'])
            ->middleware('throttle:30,1')
            ->name('blog.view');
        Route::get('/contact', [MessageController::class, 'show'])->name('contact');
        Route::post('/contact', [MessageController::class, 'store'])
            ->middleware('throttle:5,1')
            ->name('contact.store');
        Route::inertia('/mentions-legales', 'LegalNotice')->name('legal-notice');
        Route::inertia('/confidentialite', 'Privacy')->name('privacy');
    });
