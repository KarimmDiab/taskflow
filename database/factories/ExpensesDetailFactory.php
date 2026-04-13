<?php

namespace Database\Factories;

use App\Models\ExpensesDetail;
use App\Models\ExpensesItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpensesDetail>
 */
class ExpensesDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expenses_date' => fake()->date(),
            'expenses_cost' => fake()->numberBetween(300 , 100),
            'expenses_note' => fake()->sentence(10),
            'expenses_item_id' => ExpensesItem::inRandomOrder()->first()->id,
            'user_id' => User::inRandomOrder()->first()->id,

        ];
    }
}
