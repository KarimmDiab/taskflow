<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SupplierPayment>
 */
class SupplierPaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_number' => 'SP-'.now()->format('Y').'-'.fake()->unique()->numerify('######'),
            'supplier_id' => Supplier::inRandomOrder()->value('id') ?? Supplier::factory(),
            'purchase_invoice_id' => PurchaseInvoice::inRandomOrder()->value('id'),
            'payment_method_id' => PaymentMethod::inRandomOrder()->value('id') ?? PaymentMethod::factory(),
            'amount' => fake()->randomFloat(2, 100, 5000),
            'payment_date' => fake()->date(),
            'reference_number' => fake()->optional()->bothify('REF-####'),
            'notes' => fake()->optional()->sentence(10),
            'created_by' => User::inRandomOrder()->value('id') ?? User::factory(),
        ];
    }
}
