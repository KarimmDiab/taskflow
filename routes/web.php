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
Route::livewire('customers', 'pages::customers.index')->middleware('auth')->name('customers');
Route::livewire('expenses_items', 'pages::expenses_item.index')->middleware('auth')->name('expenses_items');

require __DIR__.'/settings.php';
