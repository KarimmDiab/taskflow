<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


Route::view('/', 'ryo-homepage')->name('home');
Route::view('/shipping-policy', 'ryo-shipping-policy')->name('Shipping-Policy');
Route::view('/terms-of-services', 'ryo-terms-of-services')->name('terms-of-services');
Route::view('/privacy-policy', 'ryo-privacy-policy')->name('privacy-policy');
Route::view('/all_products', 'ryo-shop')->name('all-products');
Route::get('/ryo-product/{product}', [ProductController::class, 'show'])
    ->name('product');
Route::view('/ryo-checkout', 'ryo-checkout')->name('checkout');
Route::view('/ryo-cart', 'ryo-cart')->name('cart');








Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

Route::livewire('branches', 'pages::branches.index')->middleware('auth')->name('branches');
Route::livewire('users', 'pages::users.index')->middleware('auth')->name('users');
Route::livewire('suppliers', 'pages::suppliers.index')->middleware('auth')->name('suppliers');
Route::livewire('categories', 'pages::categories.index')->middleware('auth')->name('categories');
Route::livewire('subCategories', 'pages::sub_categories.index')->middleware('auth')->name('subCategories');
Route::livewire('customers', 'pages::customers.index')->middleware('auth')->name('customers');
Route::livewire('expenses_items', 'pages::expenses_item.index')->middleware('auth')->name('expenses_items');
Route::livewire('products', 'pages::products.index')->middleware('auth')->name('products');
Route::livewire('expenses', 'pages::expenses.index')->middleware('auth')->name('expenses');
Route::livewire('purchaseInvoices', 'pages::purchases.index')->middleware('auth')->name('purchaseInvoices');

Route::livewire('createpurchaseInvoices', 'pages::purchases.create')->middleware('auth')->name('createpurchaseInvoices');

require __DIR__.'/settings.php';
