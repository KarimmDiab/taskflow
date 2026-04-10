<?php

namespace Database\Factories;

use App\Models\Branches;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_name' => fake()->name(),
            'product_quantity' => fake()->numberBetween(5 , 15),
            'product_price' => fake()->numberBetween(400 , 900),
            'category_id' => Category::inRandomOrder()->first()->id,
            'branch_id' => Branches::inRandomOrder()->first()->id,



        ];
    }
}
