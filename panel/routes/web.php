<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::view('dashboard/devices', 'devices')
    ->middleware(['auth', 'verified'])
    ->name('devices');

Route::view('dashboard/api-keys', 'api-keys')
    ->middleware(['auth', 'verified'])
    ->name('api-keys');

Route::view('dashboard/webhooks', 'webhooks')
    ->middleware(['auth', 'verified'])
    ->name('webhooks');

Route::view('dashboard/billing', 'subscriptions')
    ->middleware(['auth', 'verified'])
    ->name('billing');

Route::view('dashboard/admin', 'admin')
    ->middleware(['auth', 'verified', 'admin'])
    ->name('admin');

Route::view('docs', 'docs')
    ->middleware(['auth', 'verified'])
    ->name('docs');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
