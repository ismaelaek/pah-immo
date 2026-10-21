<?php

declare(strict_types=1);

use App\Enums\PropertyType;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Home', [
    'propertyTypes' => PropertyType::options(),
])->name('home');

Route::inertia('/about', 'About')->name('about');
Route::inertia('/properties', 'Properties')->name('properties');
Route::inertia('/blog', 'Blog')->name('blog');
Route::inertia('/contact', 'Contact')->name('contact');
