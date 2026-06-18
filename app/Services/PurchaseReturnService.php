<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceDetail;
use App\Models\PurchaseReturn;
use App\Models\PurchaseReturnItem;
use App\Models\SupplierCredit;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseReturnService
{
    /**
     * @param  array<string, mixed>  $data
     * @param  array<int, array<string, mixed>>  $items
     */
    public function create(array $data, array $items): PurchaseReturn
    {
        return DB::transaction(function () use ($data, $items): PurchaseReturn {
            $invoice = PurchaseInvoice::query()
                ->with('purchaseInvoiceDetails')
                ->whereKey($data['purchase_invoice_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $preparedItems = $this->prepareItems($invoice, $items);

            if ($preparedItems->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'Select at least one item quantity to return.']);
            }

            $total = round((float) $preparedItems->sum('total'), 2);

            $purchaseReturn = PurchaseReturn::create([
                'return_number' => $this->nextReturnNumber(),
                'supplier_id' => $invoice->supplier_id,
                'purchase_invoice_id' => $invoice->id,
                'return_date' => $data['return_date'],
                'total_amount' => $total,
                'notes' => $data['notes'] ?? null,
                'created_by' => $data['created_by'],
            ]);

            foreach ($preparedItems as $item) {
                PurchaseReturnItem::create([
                    'purchase_return_id' => $purchaseReturn->id,
                    'purchase_invoice_item_id' => $item['detail']->id,
                    'product_variant_id' => $item['detail']->product_variant_id,
                    'quantity' => $item['quantity'],
                    'unit_cost' => $item['unit_cost'],
                    'total' => $item['total'],
                    'reason' => $item['reason'],
                ]);

                app(StockMovementService::class)->recordPurchaseReturn(
                    (int) ($item['detail']->branch_id ?: $invoice->branch_id),
                    (int) $item['detail']->product_variant_id,
                    (int) $item['quantity'],
                    (float) $item['unit_cost'],
                    $purchaseReturn,
                    "Purchase return {$purchaseReturn->return_number} for invoice {$invoice->invoice_number}",
                    true,
                );

                $this->refreshProductQuantity((int) $item['detail']->productVariant->product_id);
            }

            $remainingBeforeReturn = round((float) $invoice->remaining_amount, 2);
            $newRemaining = max($remainingBeforeReturn - $total, 0);
            $creditAmount = max($total - $remainingBeforeReturn, 0);

            $invoice->forceFill([
                'remaining_amount' => round($newRemaining, 2),
            ])->save();

            if ($creditAmount > 0) {
                SupplierCredit::create([
                    'credit_number' => $this->nextCreditNumber(),
                    'supplier_id' => $invoice->supplier_id,
                    'purchase_invoice_id' => $invoice->id,
                    'purchase_return_id' => $purchaseReturn->id,
                    'amount' => round($creditAmount, 2),
                    'remaining_amount' => round($creditAmount, 2),
                    'credit_date' => $data['return_date'],
                    'notes' => "Credit generated from purchase return {$purchaseReturn->return_number}.",
                    'created_by' => $data['created_by'],
                ]);
            }

            return $purchaseReturn->load([
                'supplier',
                'purchaseInvoice',
                'items.productVariant.product',
                'items.productVariant.color',
                'items.productVariant.size',
                'stockMovements',
                'supplierCredit',
                'createdBy',
            ]);
        });
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     */
    private function prepareItems(PurchaseInvoice $invoice, array $items)
    {
        return collect($items)
            ->map(function (array $item) use ($invoice): ?array {
                $quantity = (int) ($item['quantity'] ?? 0);
                if ($quantity <= 0) {
                    return null;
                }

                $detail = PurchaseInvoiceDetail::query()
                    ->with(['productVariant'])
                    ->where('purchase_invoice_id', $invoice->id)
                    ->whereKey($item['purchase_invoice_item_id'] ?? null)
                    ->lockForUpdate()
                    ->first();

                if (! $detail) {
                    throw ValidationException::withMessages(['items' => 'One of the selected invoice items is invalid.']);
                }

                $alreadyReturned = (int) PurchaseReturnItem::query()
                    ->where('purchase_invoice_item_id', $detail->id)
                    ->sum('quantity');
                $available = max((int) $detail->product_quantity - $alreadyReturned, 0);

                if ($quantity > $available) {
                    throw ValidationException::withMessages([
                        "returnItems.{$detail->id}.quantity" => "Return quantity cannot exceed available quantity ({$available}).",
                    ]);
                }

                $unitCost = round((float) $detail->unit_cost, 2);

                return [
                    'detail' => $detail,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'total' => round($quantity * $unitCost, 2),
                    'reason' => $item['reason'] ?? null,
                ];
            })
            ->filter()
            ->values();
    }

    private function refreshProductQuantity(int $productId): void
    {
        $totalQuantity = Inventory::query()
            ->join('product_variants', 'inventories.product_variant_id', '=', 'product_variants.id')
            ->where('product_variants.product_id', $productId)
            ->sum('inventories.quantity');

        Product::query()->whereKey($productId)->update(['product_quantity' => $totalQuantity]);
    }

    private function nextReturnNumber(): string
    {
        $prefix = 'PR-'.now()->format('Y').'-';
        $latest = PurchaseReturn::query()
            ->where('return_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('return_number')
            ->value('return_number');

        $next = 1;
        if ($latest && preg_match('/^PR-\d{4}-(\d{6})$/', $latest, $matches)) {
            $next = (int) $matches[1] + 1;
        }

        return $prefix.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }

    private function nextCreditNumber(): string
    {
        $prefix = 'SC-'.now()->format('Y').'-';
        $latest = SupplierCredit::query()
            ->where('credit_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('credit_number')
            ->value('credit_number');

        $next = 1;
        if ($latest && preg_match('/^SC-\d{4}-(\d{6})$/', $latest, $matches)) {
            $next = (int) $matches[1] + 1;
        }

        return $prefix.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}
