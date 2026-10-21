<?php

declare(strict_types=1);

use App\Enums\PropertyType;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home', [
    'propertyTypes' => PropertyType::options(),
])->name('home');

Route::inertia('/about', 'About')->name('about');
Route::inertia('/properties', 'Properties')->name('properties');
Route::inertia('/blog', 'Blog')->name('blog');
Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');
Route::inertia('/mentions-legales', 'LegalNotice')->name('legal-notice');
Route::inertia('/confidentialite', 'Privacy')->name('privacy');
