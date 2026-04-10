<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseInvoiceDetail>
 */
class PurchaseInvoiceDetailFactory extends Factory
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
            'purchase_invoice_id' => PurchaseInvoice::inRandomOrder()->first()->id,
            'product_quantity' => fake()->numberBetween(5,15),
            'cost_per_piece' => fake()->numberBetween(500,1000),
            

        ];
    }
}
