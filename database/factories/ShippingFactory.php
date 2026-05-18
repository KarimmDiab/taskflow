<?php

namespace Database\Factories;

use App\Models\Shipping;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shipping>
 */
class ShippingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city_name' =>$this->faker->city(),
            'shipping_cost' => $this->faker->numberBetween(50, 120),
            'is_active' => $this->faker->boolean(),
            'estimated_days' => "3-5 bussiness days",
        ];
    }
}
