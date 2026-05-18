<?php

namespace Database\Factories;

use App\Models\OnlineOrder;
use App\Models\SalesInvoice;
use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OnlineOrder>
 */
class OnlineOrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sales_invoice_id' => SalesInvoice::factory(),
            'shipping_id' => Shipping::factory(),
            'shipping_cost' => fake()->numberBetween(30, 200),
            'address' => fake()->address(),
            'area' => fake()->city(),
            'order_note' => fake()->optional()->sentence(),
            'status' => fake()->randomElement([
                'pending',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
            ]),
            'customer_name' => fake()->name(),
            'customer_phone' => fake()->phoneNumber(),
            'customer_email' => fake()->optional()->safeEmail(),
        ];
    }
}
