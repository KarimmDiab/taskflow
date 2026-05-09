<?php

namespace Database\Seeders;

use App\Models\StockTransferItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockTransferItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockTransferItem::factory()->count(10)->create();
    }
}
