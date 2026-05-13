<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(10)->create();

        $this->call([
            SupplierSeeder::class,
            ColorSeeder::class,
            SizeSeeder::class,
            PaymentMethodSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            BranchesSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            PurchaseInvoiceSeeder::class,
            PurchaseInvoiceDetailSeeder::class,
            SupplierPaymentSeeder::class,
            CustomerSeeder::class,
            SalesInvoiceSeeder::class,
            SalesInvoiceDetailSeeder::class,
            CustomerTransactionSeeder::class,
            ExpensesItemSeeder::class,
            ExpensesDetailSeeder::class,
            RolePermissionSeeder::class,
            ProductImageSeeder::class,
            InventorySeeder::class,
            StockTransferSeeder::class,
            StockTransferItemSeeder::class,
            CollectionSeeder::class,

        ]);

        $admin = User::factory()->create([
            'name' => 'karim diab',
            'email' => 'karim@karim.com',
            'password' => '12345678',
        ]);
        $admin->assignRole('admin');
    }
}
