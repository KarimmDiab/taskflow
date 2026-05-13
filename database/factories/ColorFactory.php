<?php

namespace Database\Factories;

use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Color>
 */
class ColorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'color_name' => $this->faker->colorName(),
            'color_hex_code' => $this->faker->hexColor(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
