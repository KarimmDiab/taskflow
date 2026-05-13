<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory()->create()->id,
            'color_id' => Color::factory()->create()->id,
            'size_id' => Size::factory()->create()->id,
            'sku' => fake()->unique()->bothify('sku-####'),
            'variant_cost' => fake()->randomFloat(2, 10, 100),
            'variant_price' =>fake()->randomFloat(2, 20, 200),
            'is_active' => true,

        ];
    }
}
