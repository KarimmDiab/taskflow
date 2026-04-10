<?php

namespace Database\Seeders;

use App\Models\ExpensesDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExpensesDetail::factory(10)->create();
    }
}
