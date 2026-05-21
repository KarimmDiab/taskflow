<?php

use App\Http\Controllers\ProductController;
use App\Models\Product;
use App\Http\Controllers\CollectionController;

use Illuminate\Support\Facades\Route;


Route::view('/', 'ryo-homepage')->name('home');
Route::view('/shipping-policy', 'ryo-shipping-policy')->name('Shipping-Policy');
Route::view('/contact-us', 'ryo-contact-us')->name('contact-us');
Route::view('/about-us', 'ryo-about-us')->name('about-us');
Route::view('/FAQs', 'ryo-FAQs')->name('FAQs');
Route::view('/polices', 'polices')->name('polices');
Route::view('/refund-policy', 'ryo-refund-policy')->name('refund-policy');




Route::view('/terms-of-services', 'ryo-terms-of-services')->name('terms-of-services');
Route::view('/privacy-policy', 'ryo-privacy-policy')->name('privacy-policy');
Route::view('/all_products', 'ryo-shop')->name('all-products');
Route::get('/ryo-product/{product}', [ProductController::class, 'show'])
    ->name('product');
Route::view('/ryo-checkout', 'ryo-checkout')->name('checkout');
Route::get('/ryo-collections', [ProductController::class, 'showAllCollection'])->name('collections');
//Route::view('/ryo-cart', 'ryo-cart')->name('cart');

Route::get('/ryo-cart', [ProductController::class, 'index'])->name('cart');


Route::get('/collections/{slug}', [CollectionController::class, 'show'])
    ->name('collection.show');






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
