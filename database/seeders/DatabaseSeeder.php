<?php

namespace Database\Seeders;

use App\Models\Branches;
use App\Models\Category;
use App\Models\Supplier;
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

        User::factory()->create([
            'name' => 'karim diab',
            'email' => 'karim@karim.com',
            'password' => '12345678'
        ]);

        User::factory(10)->create();

        $this->call([
            SupplierSeeder::class,
            CategorySeeder::class,
            BranchesSeeder::class,
            ProductSeeder::class,
            PurchaseInvoiceSeeder::class,
            PurchaseInvoiceDetailSeeder::class,
            SupplierPaymentSeeder::class,
            SalesInvoiceSeeder::class,
            SalesInvoiceDetailSeeder::class,
            CustomerSeeder::class,
            CustomerSalesInvoiceSeeder::class,
            CustomerTransactionSeeder::class,
            ExpensesItemSeeder::class,
            ExpensesDetailSeeder::class
        ]);
    }
}
