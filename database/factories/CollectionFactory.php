<?php

namespace Database\Factories;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Collection>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'collection_name' => $this->faker->word(),
            'slug' => $this->faker->slug(),
            'collection_desc' =>$this->faker->sentence(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
