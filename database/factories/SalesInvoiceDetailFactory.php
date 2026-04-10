<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesInvoiceDetail>
 */
class SalesInvoiceDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::inRandomOrder()->first()->id,
            'sales_invoice_id' => SalesInvoice::inRandomOrder()->first()->id,
            'product_quantity' => fake()->numberBetween(5,30),
            'cost_per_piece' => fake()->numberBetween(500 , 1500),
        ];
    }
}
