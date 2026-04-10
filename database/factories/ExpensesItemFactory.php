<?php

namespace Database\Factories;

use App\Models\ExpensesItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ExpensesItem>
 */
class ExpensesItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'expenses_name' => fake()->name()
        ];
    }
}
