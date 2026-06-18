<?php

namespace App\Services;

use App\Models\Inventory;
use RuntimeException;

class InventoryService
{
    public function adjust(int $productVariantId, int $branchId, int $quantityDelta): Inventory
    {
        $inventory = Inventory::query()
            ->where('product_variant_id', $productVariantId)
            ->where('branch_id', $branchId)
            ->lockForUpdate()
            ->first();

        if (! $inventory) {
            if ($quantityDelta < 0) {
                throw new RuntimeException('Inventory record was not found for this branch.');
            }

            return Inventory::create([
                'product_variant_id' => $productVariantId,
                'branch_id' => $branchId,
                'quantity' => $quantityDelta,
            ]);
        }

        $newQuantity = (int) $inventory->quantity + $quantityDelta;

        if ($newQuantity < 0) {
            throw new RuntimeException('Selected quantity exceeds available stock.');
        }

        $inventory->update(['quantity' => $newQuantity]);

        return $inventory;
    }

    public function available(int $productVariantId, int $branchId): int
    {
        return (int) Inventory::query()
            ->where('product_variant_id', $productVariantId)
            ->where('branch_id', $branchId)
            ->value('quantity');
    }
}
