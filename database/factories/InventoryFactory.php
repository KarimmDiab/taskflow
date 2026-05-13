<?php

namespace Database\Factories;

use App\Models\Branches;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $existing = Inventory::select('product_variant_id', 'branch_id')
            ->get()
            ->map(fn ($item) => $item->product_variant_id.'-'.$item->branch_id)
            ->toArray();

        $combinations = [];

        foreach (ProductVariant::all() as $product) {
            foreach (Branches::all() as $branch) {

                $key = $product->id.'-'.$branch->id;

                if (! in_array($key, $existing)) {
                    $combinations[] = [
                        'product_variant_id' => $product->id,
                        'branch_id' => $branch->id,
                    ];
                }
            }
        }

        if (empty($combinations)) {
            throw new \Exception('No available inventory combinations left.');
        }

        $random = fake()->randomElement($combinations);

        return [
            'product_variant_id' => $random['product_variant_id'],
            'branch_id' => $random['branch_id'],
            'quantity' => fake()->numberBetween(0, 100),
        ];
    }
}
