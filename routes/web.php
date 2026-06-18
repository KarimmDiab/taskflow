<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\ProductController;
use App\Livewire\Admin\Notifications\Index;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomePageController::class, 'index'])->name('home');

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

Route::get('/ryo-checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/ryo-checkout', [CheckoutController::class, 'store'])->name('checkout.store');

Route::get('/ryo-collections', [ProductController::class, 'showAllCollection'])->name('collections');

Route::get('/ryo-cart', [ProductController::class, 'index'])->name('cart');

Route::get('/collections/{slug}', [CollectionController::class, 'show'])
    ->name('collection.show');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->middleware('permission:dashboard.view')
        ->name('dashboard');
});

Route::livewire('branches', 'pages::branches.index')->middleware(['auth', 'permission:branches.view'])->name('branches');
Route::livewire('users', 'pages::users.index')->middleware(['auth', 'permission:users.view'])->name('users');
Route::livewire('suppliers', 'pages::suppliers.index')->middleware(['auth', 'permission:suppliers.view'])->name('suppliers');
Route::livewire('categories', 'pages::categories.index')->middleware(['auth', 'permission:main_categories.view'])->name('categories');
Route::livewire('subCategories', 'pages::sub_categories.index')->middleware(['auth', 'permission:sub_categories.view'])->name('subCategories');
Route::livewire('customers', 'pages::customers.index')->middleware(['auth', 'permission:customers.view'])->name('customers');
Route::livewire('expenses_items', 'pages::expenses_item.index')->middleware(['auth', 'permission:expense_items.view'])->name('expenses_items');
Route::livewire('products/create', 'product.create')->middleware(['auth', 'permission:products.create'])->name('products.create');
Route::livewire('colors', 'pages::colors.index')->middleware(['auth', 'permission:colors.view'])->name('colors');
Route::livewire('sizes', 'pages::sizes.index')->middleware(['auth', 'permission:sizes.view'])->name('sizes');
Route::livewire('payment_methods', 'pages::payment_method.index')->middleware(['auth', 'permission:payment_methods.view'])->name('payment_methods');
Route::livewire('shipping', 'pages::shipping.index')->middleware(['auth', 'permission:shipping_governorates.view'])->name('shipping');
Route::livewire('all_collections', 'pages::collection.index')->middleware(['auth', 'permission:collections.view'])->name('all_collections');
Route::livewire('pos_system', 'pages::pos_system.index')->middleware(['auth', 'permission:pos_system.view'])->name('pos_system');
Route::livewire('stock-movements', 'pages::stock_movements.index')->middleware(['auth', 'permission:stock_movements.view'])->name('stock-movements.index');
Route::livewire('inventory-adjustments', 'pages::inventory_adjustments.index')->middleware(['auth', 'permission:inventory_adjustments.view'])->name('inventory-adjustments.index');
Route::livewire('stock-transfers', 'pages::stock_transfers.index')->middleware(['auth', 'permission:stock_transfers.view'])->name('stock-transfers.index');
Route::view('stock-transfers/{transfer}/print', 'pages.stock_transfers.print')->middleware(['auth', 'permission:stock_transfers.print'])->name('stock-transfers.print');
Route::livewire('barcodes', 'pages::barcodes.index')->middleware(['auth', 'permission:barcodes.view'])->name('barcodes.index');
Route::view('barcodes/print/labels', 'pages.barcodes.print')->middleware(['auth', 'permission:barcodes.print'])->name('barcodes.print');
Route::middleware(['auth'])->group(function () {
    Route::livewire('sales', 'pages::sales.index')->middleware('permission:sales.view')->name('sales.index');
    Route::livewire('sales/{invoice}', 'pages::sales.show')->middleware('permission:sales.view')->name('sales.show');
    Route::livewire('sales/{invoice}/edit', 'pages::sales.edit')->middleware('permission:sales.edit')->name('sales.edit');
    Route::livewire('sales-returns', 'pages::sales.returns')->middleware('permission:sales.return.view')->name('sales.returns');
    Route::livewire('sales-reports', 'pages::sales.reports')->middleware('permission:sales.view')->name('sales.reports');
    Route::view('sales/{invoice}/print/a4', 'pages.sales.print-a4')->middleware('permission:sales.print')->name('sales.print.a4');
    Route::view('sales/{invoice}/print/receipt', 'pages.sales.print-receipt')->middleware('permission:sales.print')->name('sales.print.receipt');
    Route::view('sales-returns/{return}/print', 'pages.sales.return-receipt')->middleware('permission:sales.print')->name('sales.returns.print');
});
Route::livewire('orders', 'pages::orders.index')->middleware(['auth', 'permission:orders.view'])->name('orders');
Route::get('admin/notifications', Index::class)
    ->middleware(['auth', 'permission:notifications.view'])
    ->name('admin.notifications');

Route::livewire('product/edit/{id}', 'product.edit')->middleware(['auth', 'permission:products.update'])->name('products.edit');

Route::livewire('products', 'pages::products.index')->middleware(['auth', 'permission:products.view'])->name('products');
Route::livewire('expenses', 'pages::expenses.index')->middleware(['auth', 'permission:expenses.view'])->name('expenses');
Route::livewire('purchaseInvoices', 'pages::purchases.index')->middleware(['auth', 'permission:purchase_invoices.view'])->name('purchaseInvoices');

Route::livewire('createpurchaseInvoices', 'pages::purchases.create')->middleware(['auth', 'permission:create_purchase_invoice.create'])->name('createpurchaseInvoices');

Route::prefix('admin')->middleware(['auth', 'permission:users.update'])->group(function () {
    Route::livewire('roles', 'pages::roles.index')->name('roles.index');
    Route::livewire('roles-permissions', 'pages::roles_permissions.index')->name('roles-permissions.index');
    Route::livewire('user-roles', 'pages::user_roles.index')->name('user-roles.index');
});

require __DIR__.'/settings.php';
