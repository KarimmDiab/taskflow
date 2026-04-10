<?php

namespace Database\Seeders;

use App\Models\SalesInvoiceDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesInvoiceDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SalesInvoiceDetail::factory(10)->create();
    }
}
