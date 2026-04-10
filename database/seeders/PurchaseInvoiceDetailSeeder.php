<?php

namespace Database\Seeders;

use App\Models\PurchaseInvoiceDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PurchaseInvoiceDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PurchaseInvoiceDetail::factory(10)->create();
    }
}
