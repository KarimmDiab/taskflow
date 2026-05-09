<?php

namespace Database\Factories;

use App\Models\Branches;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\SalesInvoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SalesInvoice>
 */
class SalesInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $total = fake()->numberBetween(1000, 5000);
        $deduction = fake()->numberBetween(0, $total);
        $net = $total - $deduction;

        // paid لازم يكون ≤ net
        $paid = fake()->numberBetween(0, $net);

        return [
            'total_amount' => $total,
            'deduction' => $deduction,
            'net_total' => $net,
            'paid_amount' => $paid,
            'remaining_amount' => $net - $paid,
            'payment_method_id' => PaymentMethod::inRandomOrder()->first()->id,
            'customer_id' => Customer::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->value('id'),
            'branch_id' => Branches::inRandomOrder()->first()->id,

        ];
    }
}
