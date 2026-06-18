<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryAdjustmentService
{
    public function __construct(
        private readonly StockMovementService $stockMovements,
    ) {}

    public function create(array $data): InventoryAdjustment
    {
        return DB::transaction(function () use ($data): InventoryAdjustment {
            $branchId = (int) $data['branch_id'];
            $variantId = (int) $data['product_variant_id'];
            $newQuantity = (int) $data['quantity_after'];

            if (! in_array($data['adjustment_type'] ?? null, InventoryAdjustment::TYPES, true)) {
                throw ValidationException::withMessages(['adjustment_type' => 'Invalid adjustment type.']);
            }

            if ($newQuantity < 0) {
                throw ValidationException::withMessages(['quantity_after' => 'New quantity cannot be negative.']);
            }

            $inventory = Inventory::query()
                ->where('branch_id', $branchId)
                ->where('product_variant_id', $variantId)
                ->lockForUpdate()
                ->first();

            $currentQuantity = (int) ($inventory?->quantity ?? 0);
            $difference = $newQuantity - $currentQuantity;
            $createdBy = $data['created_by'] ?? auth()->id();

            if ($difference === 0) {
                throw ValidationException::withMessages(['quantity_after' => 'New quantity must be different from current quantity.']);
            }

            if (! $createdBy) {
                throw ValidationException::withMessages(['created_by' => 'A valid user is required to create inventory adjustments.']);
            }

            if ($newQuantity < 0 && ! config('inventory.allow_negative_stock', false)) {
                throw ValidationException::withMessages(['quantity_after' => 'Adjustment would create negative stock.']);
            }

            $adjustment = InventoryAdjustment::create([
                'adjustment_number' => $data['adjustment_number'] ?? $this->generateAdjustmentNumber(),
                'branch_id' => $branchId,
                'product_variant_id' => $variantId,
                'quantity_before' => $currentQuantity,
                'quantity_after' => $newQuantity,
                'adjustment_quantity' => $difference,
                'adjustment_type' => $data['adjustment_type'],
                'reason' => $data['reason'],
                'notes' => $data['notes'] ?? null,
                'status' => 'completed',
                'created_by' => $createdBy,
                'adjustment_date' => $data['adjustment_date'] ?? now(),
            ]);

            $this->stockMovements->recordAdjustment(
                $branchId,
                $variantId,
                abs($difference),
                $difference > 0 ? 'in' : 'out',
                $adjustment,
                trim($data['reason'].' '.($data['notes'] ?? '')),
                true,
            );

            $this->syncProductQuantity($variantId);

            return $adjustment->fresh(['branch', 'productVariant.product', 'productVariant.color', 'productVariant.size', 'createdBy', 'stockMovement']);
        });
    }

    private function syncProductQuantity(int $variantId): void
    {
        $productId = ProductVariant::query()->whereKey($variantId)->value('product_id');

        if (! $productId) {
            return;
        }

        $totalQuantity = DB::table('inventories')
            ->join('product_variants', 'inventories.product_variant_id', '=', 'product_variants.id')
            ->where('product_variants.product_id', $productId)
            ->sum('inventories.quantity');

        Product::whereKey($productId)->update(['product_quantity' => $totalQuantity]);
    }

    private function generateAdjustmentNumber(): string
    {
        $prefix = 'ADJ-'.now()->format('Ymd').'-';

        do {
            $number = $prefix.str_pad((string) random_int(1, 99999), 5, '0', STR_PAD_LEFT);
        } while (InventoryAdjustment::where('adjustment_number', $number)->exists());

        return $number;
    }
}
