<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\Notification;
use App\Services\NotificationService;

class InventoryObserver
{
    public function created(Inventory $inventory): void
    {
        $this->handleStockLevel($inventory);
    }

    public function updated(Inventory $inventory): void
    {
        if (! $inventory->wasChanged('quantity')) {
            return;
        }

        $this->handleStockLevel($inventory);
    }

    private function handleStockLevel(Inventory $inventory): void
    {
        $inventory->loadMissing('productVariant.product', 'productVariant.color', 'productVariant.size');

        $variant = $inventory->productVariant;
        $product = $variant?->product;

        if (! $variant || ! $product) {
            return;
        }

        $quantity = (int) $inventory->quantity;
        $minimumStock = (int) ($product->minimum_stock ?? 0);

        $this->clearRecoveredAlerts($variant->id, $quantity, $minimumStock);

        if ($quantity === 0) {
            $this->createOutOfStockAlert($inventory);
            return;
        }

        if ($minimumStock > 0 && $quantity <= $minimumStock) {
            $this->createLowStockAlert($inventory);
        }
    }

    private function clearRecoveredAlerts(int $variantId, int $quantity, int $minimumStock): void
    {
        if ($quantity > 0) {
            $this->markStockAlertAsRecovered($variantId, 'out_of_stock');
        }

        if ($minimumStock > 0 && $quantity > $minimumStock) {
            $this->markStockAlertAsRecovered($variantId, 'low_stock');
        }
    }

    private function createLowStockAlert(Inventory $inventory): void
    {
        $variant = $inventory->productVariant;
        $product = $variant->product;
        $label = $this->variantLabel($inventory);

        app(NotificationService::class)->createUniqueUnread(
            title: 'Low Stock Alert',
            message: "{$label} reached low stock: {$inventory->quantity} pcs",
            type: 'low_stock',
            module: 'inventory',
            referenceId: $variant->id,
            data: [
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'branch_id' => $inventory->branch_id,
                'quantity' => (int) $inventory->quantity,
                'minimum_stock' => (int) $product->minimum_stock,
            ],
        );
    }

    private function createOutOfStockAlert(Inventory $inventory): void
    {
        $variant = $inventory->productVariant;
        $product = $variant->product;
        $label = $this->variantLabel($inventory);

        app(NotificationService::class)->createUniqueUnread(
            title: 'Out Of Stock',
            message: "{$label} is out of stock",
            type: 'out_of_stock',
            module: 'inventory',
            referenceId: $variant->id,
            data: [
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'branch_id' => $inventory->branch_id,
                'quantity' => 0,
            ],
        );
    }

    private function markStockAlertAsRecovered(int $variantId, string $type): void
    {
        Notification::query()
            ->unread()
            ->type($type)
            ->module('inventory')
            ->where('reference_id', $variantId)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    private function variantLabel(Inventory $inventory): string
    {
        $variant = $inventory->productVariant;
        $productName = $variant?->product?->product_name ?? 'Product';
        $color = $variant?->color?->color_name ?? 'No color';
        $size = $variant?->size?->size_name ?? 'No size';

        return "{$productName} - {$color} - {$size}";
    }
}
