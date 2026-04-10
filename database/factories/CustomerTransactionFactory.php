<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerTransaction;
use App\Models\SalesInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerTransaction>
 */
class CustomerTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'payment_date' => fake()->date(),
            'paid_amount' => fake()->numberBetween(1000, 5000),
            'note' => fake()->sentence(10),
            'sales_invoice_id' => SalesInvoice::inRandomOrder()->first()->id,   
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'user_id' => 1,
        ];
    }
}
