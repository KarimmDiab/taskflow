<?php

namespace Database\Factories;

use App\Models\Branches;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PurchaseInvoice>
 */
class PurchaseInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = fake()->numberBetween(1000, 5000);
        $paid = fake()->numberBetween(0, $total);

        return [
            'purchase_invoice_date' => fake()->date(),
            'total_amount' => $total,
            'paid_amount' => $paid,
            'payment_method' => fake()->randomElement(['visa', 'credit', 'cash', 'instapay']),
            'remaining_amount' => $total - $paid,
            'supplier_id' => Supplier::inRandomOrder()->first()->id,
            'branch_id' => Branches::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
