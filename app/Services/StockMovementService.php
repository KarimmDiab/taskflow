<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\ProductVariant;
use App\Models\StockMovement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class StockMovementService
{
    public function recordMovement(array $data): StockMovement
    {
        $this->validatePayload($data);

        return DB::transaction(function () use ($data): StockMovement {
            $quantity = (int) $data['quantity'];
            $branchId = (int) $data['branch_id'];
            $variantId = (int) $data['product_variant_id'];
            $direction = $data['direction'];
            $shouldUpdateInventory = (bool) ($data['update_inventory'] ?? false);

            $variant = ProductVariant::query()->findOrFail($variantId);
            $inventory = Inventory::query()
                ->where('branch_id', $branchId)
                ->where('product_variant_id', $variantId)
                ->lockForUpdate()
                ->first();

            $before = (int) ($inventory?->quantity ?? 0);
            $after = $direction === 'in' ? $before + $quantity : $before - $quantity;

            if ($after < 0 && ! config('inventory.allow_negative_stock', false)) {
                throw new RuntimeException('Stock movement would create negative stock.');
            }

            if ($shouldUpdateInventory) {
                if ($inventory) {
                    $inventory->update(['quantity' => $after]);
                } else {
                    $inventory = Inventory::create([
                        'branch_id' => $branchId,
                        'product_variant_id' => $variantId,
                        'quantity' => $after,
                    ]);
                }
            }

            return StockMovement::create([
                'branch_id' => $branchId,
                'product_id' => $data['product_id'] ?? $variant->product_id,
                'product_variant_id' => $variantId,
                'movement_type' => $data['movement_type'],
                'direction' => $direction,
                'quantity' => $quantity,
                'quantity_before' => $before,
                'quantity_after' => $after,
                'unit_cost' => $data['unit_cost'] ?? null,
                'unit_price' => $data['unit_price'] ?? null,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => $data['created_by'] ?? auth()->id(),
                'movement_date' => $data['movement_date'] ?? now(),
            ]);
        });
    }

    public function recordPurchase(int $branchId, int $variantId, int $quantity, ?float $unitCost = null, ?Model $reference = null, ?string $notes = null, bool $updateInventory = false): StockMovement
    {
        return $this->recordTypedMovement('purchase', 'in', $branchId, $variantId, $quantity, $reference, $notes, $updateInventory, unitCost: $unitCost);
    }

    public function recordSale(int $branchId, int $variantId, int $quantity, ?float $unitPrice = null, ?Model $reference = null, ?string $notes = null, bool $updateInventory = false): StockMovement
    {
        return $this->recordTypedMovement('sale', 'out', $branchId, $variantId, $quantity, $reference, $notes, $updateInventory, unitPrice: $unitPrice);
    }

    public function recordSalesReturn(int $branchId, int $variantId, int $quantity, ?float $unitPrice = null, ?Model $reference = null, ?string $notes = null, bool $updateInventory = false): StockMovement
    {
        return $this->recordTypedMovement('sales_return', 'in', $branchId, $variantId, $quantity, $reference, $notes, $updateInventory, unitPrice: $unitPrice);
    }

    public function recordPurchaseReturn(int $branchId, int $variantId, int $quantity, ?float $unitCost = null, ?Model $reference = null, ?string $notes = null, bool $updateInventory = false): StockMovement
    {
        return $this->recordTypedMovement('purchase_return', 'out', $branchId, $variantId, $quantity, $reference, $notes, $updateInventory, unitCost: $unitCost);
    }

    public function recordTransferIn(int $branchId, int $variantId, int $quantity, ?Model $reference = null, ?string $notes = null, bool $updateInventory = false): StockMovement
    {
        return $this->recordTypedMovement('transfer_in', 'in', $branchId, $variantId, $quantity, $reference, $notes, $updateInventory);
    }

    public function recordTransferOut(int $branchId, int $variantId, int $quantity, ?Model $reference = null, ?string $notes = null, bool $updateInventory = false): StockMovement
    {
        return $this->recordTypedMovement('transfer_out', 'out', $branchId, $variantId, $quantity, $reference, $notes, $updateInventory);
    }

    public function recordAdjustment(int $branchId, int $variantId, int $quantity, string $direction, ?Model $reference = null, ?string $notes = null, bool $updateInventory = false): StockMovement
    {
        return $this->recordTypedMovement('adjustment', $direction, $branchId, $variantId, $quantity, $reference, $notes, $updateInventory);
    }

    private function recordTypedMovement(
        string $type,
        string $direction,
        int $branchId,
        int $variantId,
        int $quantity,
        ?Model $reference,
        ?string $notes,
        bool $updateInventory,
        ?float $unitCost = null,
        ?float $unitPrice = null,
    ): StockMovement {
        return $this->recordMovement([
            'branch_id' => $branchId,
            'product_variant_id' => $variantId,
            'movement_type' => $type,
            'direction' => $direction,
            'quantity' => $quantity,
            'unit_cost' => $unitCost,
            'unit_price' => $unitPrice,
            'reference_type' => $reference?->getMorphClass(),
            'reference_id' => $reference?->getKey(),
            'notes' => $notes,
            'update_inventory' => $updateInventory,
        ]);
    }

    private function validatePayload(array $data): void
    {
        if (! in_array($data['movement_type'] ?? null, StockMovement::TYPES, true)) {
            throw new InvalidArgumentException('Invalid stock movement type.');
        }

        if (! in_array($data['direction'] ?? null, StockMovement::DIRECTIONS, true)) {
            throw new InvalidArgumentException('Invalid stock movement direction.');
        }

        if ((int) ($data['quantity'] ?? 0) <= 0) {
            throw new InvalidArgumentException('Stock movement quantity must be greater than zero.');
        }

        foreach (['branch_id', 'product_variant_id'] as $key) {
            if (empty($data[$key])) {
                throw new InvalidArgumentException("Missing {$key} for stock movement.");
            }
        }
    }
}
