<?php

namespace Database\Seeders;

use App\Models\StockTransfer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockTransferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockTransfer::factory()->count(10)->create();
    }
}
