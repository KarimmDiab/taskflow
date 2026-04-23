<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::livewire('branches', 'pages::branches.index')->middleware('auth')->name('branches');
Route::livewire('users', 'pages::users.index')->middleware('auth')->name('users');
Route::livewire('suppliers', 'pages::suppliers.index')->middleware('auth')->name('suppliers');
Route::livewire('categories', 'pages::categories.index')->middleware('auth')->name('categories');


require __DIR__.'/settings.php';
