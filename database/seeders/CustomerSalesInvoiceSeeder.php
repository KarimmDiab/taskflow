<?php

namespace Database\Seeders;

use App\Models\CustomerSalesInvoice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSalesInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CustomerSalesInvoice::factory(5)->create();
    }
}
