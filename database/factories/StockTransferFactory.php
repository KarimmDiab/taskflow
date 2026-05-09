<?php

namespace Database\Factories;

use App\Models\Branches;
use App\Models\StockTransfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StockTransfer>
 */
class StockTransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $fromBranch = Branches::inRandomOrder()->first();

        $toBranch = Branches::where('id', '!=', $fromBranch->id)
            ->inRandomOrder()
            ->first();

        return [
            'from_branch_id' => $fromBranch->id,

            'to_branch_id' => $toBranch->id,

            'transfer_date' => fake()->date(),

            'user_id' => User::inRandomOrder()->value('id'),

            'notes' => fake()->sentence(10),
        ];
    }
}
