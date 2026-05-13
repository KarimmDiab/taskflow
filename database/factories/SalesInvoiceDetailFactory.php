<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
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
            'product_variant_id' => ProductVariant::inRandomOrder()->first()->id,
            'sales_invoice_id' => SalesInvoice::inRandomOrder()->first()->id,
            'product_quantity' => fake()->numberBetween(5,30),
            'unit_price' => fake()->numberBetween(500 , 1500),
        ];
    }
}
