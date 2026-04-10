<?php

namespace Database\Seeders;

use App\Models\ExpensesItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExpensesItem::factory(10)->create();
    }
}
