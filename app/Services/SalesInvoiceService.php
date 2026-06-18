<?php

namespace App\Services;

use App\Models\ProductVariant;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalesInvoiceService
{
    public function __construct(
        private readonly InventoryService $inventory,
        private readonly StockMovementService $stockMovements,
        private readonly SalesActivityLogger $logger,
        private readonly DiscountService $discounts,
    ) {}

    public function create(array $data, array $items): SalesInvoice
    {
        return DB::transaction(function () use ($data, $items): SalesInvoice {
            $totals = $this->calculateTotals(
                $items,
                (float) ($data['discount_value'] ?? $data['discount_amount'] ?? $data['deduction'] ?? 0),
                (float) ($data['tax_amount'] ?? 0),
                $data['discount_type'] ?? 'fixed',
            );
            $paid = (float) ($data['paid_amount'] ?? 0);

            if ($paid > $totals['grand_total']) {
                throw ValidationException::withMessages(['paid_amount' => 'Paid amount cannot exceed grand total.']);
            }

            $invoice = SalesInvoice::create([
                'invoice_number' => $data['invoice_number'] ?? $this->generateInvoiceNumber(),
                'subtotal' => $totals['gross_subtotal'],
                'total_amount' => $totals['subtotal'],
                'deduction' => $totals['discount'],
                'discount_amount' => $totals['discount'],
                'discount_type' => $data['discount_type'] ?? ($totals['invoice_discount'] > 0 ? 'fixed' : null),
                'coupon_id' => $data['coupon_id'] ?? null,
                'coupon_code' => $data['coupon_code'] ?? null,
                'tax_amount' => $totals['tax'],
                'grand_total' => $totals['grand_total'],
                'net_total' => $totals['grand_total'],
                'paid_amount' => $paid,
                'remaining_amount' => max($totals['grand_total'] - $paid, 0),
                'customer_id' => $data['customer_id'],
                'payment_method_id' => $data['payment_method_id'],
                'user_id' => $data['user_id'] ?? auth()->id(),
                'branch_id' => $data['branch_id'],
                'status' => $data['status'] ?? 'completed',
            ]);

            foreach ($items as $item) {
                $this->assertStock((int) $item['product_variant_id'], (int) $data['branch_id'], (int) $item['quantity']);
                $detail = $this->createDetail($invoice, $item);
                $this->stockMovements->recordSale(
                    (int) $data['branch_id'],
                    (int) $item['product_variant_id'],
                    (int) $item['quantity'],
                    (float) $item['unit_price'],
                    $invoice,
                    "Sale line #{$detail->id} for invoice {$invoice->invoice_number}",
                    true,
                );
            }

            $this->logger->invoice($invoice, 'created', 'Sales invoice created.');

            return $invoice->fresh(['salesInvoiceDetails.productVariant.product', 'customer', 'branch', 'paymentMethod']);
        });
    }

    public function update(SalesInvoice $invoice, array $data, array $items): SalesInvoice
    {
        if ($invoice->status === 'cancelled') {
            throw ValidationException::withMessages(['invoice' => 'Cancelled invoices cannot be edited.']);
        }

        return DB::transaction(function () use ($invoice, $data, $items): SalesInvoice {
            $invoice->load(['salesInvoiceDetails', 'coupon', 'onlineOrder.coupon']);
            $oldBranchId = (int) $invoice->branch_id;
            $newBranchId = (int) $data['branch_id'];

            foreach ($invoice->salesInvoiceDetails as $detail) {
                $this->stockMovements->recordAdjustment(
                    $oldBranchId,
                    (int) $detail->product_variant_id,
                    (int) $detail->product_quantity,
                    'in',
                    $invoice,
                    "Stock restored before editing invoice {$invoice->invoice_number}",
                    true,
                );
            }

            $totals = $this->calculateTotals(
                $items,
                (float) ($data['discount_value'] ?? $data['discount_amount'] ?? $data['deduction'] ?? 0),
                (float) ($data['tax_amount'] ?? 0),
                $data['discount_type'] ?? 'fixed',
            );
            $paid = (float) ($data['paid_amount'] ?? 0);

            if ($paid > $totals['grand_total']) {
                throw ValidationException::withMessages(['paid_amount' => 'Paid amount cannot exceed grand total.']);
            }

            foreach ($items as $item) {
                $this->assertStock((int) $item['product_variant_id'], $newBranchId, (int) $item['quantity']);
            }

            $invoice->update([
                'subtotal' => $totals['gross_subtotal'],
                'total_amount' => $totals['subtotal'],
                'deduction' => $totals['discount'],
                'discount_amount' => $totals['discount'],
                'discount_type' => $data['discount_type'] ?? ($totals['invoice_discount'] > 0 ? 'fixed' : null),
                'tax_amount' => $totals['tax'],
                'grand_total' => $totals['grand_total'],
                'net_total' => $totals['grand_total'],
                'paid_amount' => $paid,
                'remaining_amount' => max($totals['grand_total'] - $paid, 0),
                'customer_id' => $data['customer_id'],
                'payment_method_id' => $data['payment_method_id'],
                'branch_id' => $newBranchId,
            ]);

            $invoice->salesInvoiceDetails()->delete();

            foreach ($items as $item) {
                $detail = $this->createDetail($invoice, $item);
                $this->stockMovements->recordSale(
                    $newBranchId,
                    (int) $item['product_variant_id'],
                    (int) $item['quantity'],
                    (float) $item['unit_price'],
                    $invoice,
                    "Sale line #{$detail->id} after editing invoice {$invoice->invoice_number}",
                    true,
                );
            }

            $this->logger->invoice($invoice, 'updated', 'Sales invoice updated.');

            return $invoice->fresh(['salesInvoiceDetails.productVariant.product', 'customer', 'branch', 'paymentMethod']);
        });
    }

    public function cancel(SalesInvoice $invoice, string $reason): SalesInvoice
    {
        if ($invoice->status === 'cancelled') {
            throw ValidationException::withMessages(['invoice' => 'Invoice is already cancelled.']);
        }

        return DB::transaction(function () use ($invoice, $reason): SalesInvoice {
            $invoice->load('salesInvoiceDetails');

            foreach ($invoice->salesInvoiceDetails as $detail) {
                $this->stockMovements->recordAdjustment(
                    (int) $invoice->branch_id,
                    (int) $detail->product_variant_id,
                    (int) $detail->product_quantity,
                    'in',
                    $invoice,
                    "Stock restored when cancelling invoice {$invoice->invoice_number}",
                    true,
                );
            }

            $invoice->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $reason,
            ]);

            if ($invoice->coupon) {
                if (! $invoice->onlineOrder || $invoice->onlineOrder->coupon_counted_at) {
                    $this->discounts->decrementCouponUsage($invoice->coupon);
                }

                $invoice->onlineOrder?->update(['coupon_counted_at' => null]);
            }

            $this->logger->invoice($invoice, 'cancelled', $reason);

            return $invoice->fresh();
        });
    }

    public function calculateTotals(array $items, float $invoiceDiscount = 0, float $tax = 0, ?string $invoiceDiscountType = 'fixed'): array
    {
        $grossSubtotal = 0;
        $itemDiscounts = 0;

        foreach ($items as $item) {
            $lineSubtotal = (float) $item['quantity'] * (float) $item['unit_price'];
            $itemDiscount = $this->resolveItemDiscount($item, $lineSubtotal);

            $grossSubtotal += $lineSubtotal;
            $itemDiscounts += $itemDiscount;
        }

        $subtotal = max($grossSubtotal - $itemDiscounts, 0);
        $invoiceDiscount = $this->discounts->calculateAmount($invoiceDiscountType, $invoiceDiscount, $subtotal);
        $discount = min(max($itemDiscounts + $invoiceDiscount, 0), $grossSubtotal);
        $tax = max($tax, 0);
        $grandTotal = max($subtotal - $invoiceDiscount + $tax, 0);

        return [
            'gross_subtotal' => round($grossSubtotal, 2),
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'item_discount' => round($itemDiscounts, 2),
            'invoice_discount' => round($invoiceDiscount, 2),
            'tax' => round($tax, 2),
            'grand_total' => round($grandTotal, 2),
        ];
    }

    private function createDetail(SalesInvoice $invoice, array $item): SalesInvoiceDetail
    {
        $variant = ProductVariant::query()->with('product')->findOrFail((int) $item['product_variant_id']);
        $quantity = (int) $item['quantity'];
        $unitPrice = (float) $item['unit_price'];
        $lineSubtotal = $quantity * $unitPrice;
        $discount = $this->resolveItemDiscount($item, $lineSubtotal);
        $lineTotal = max($lineSubtotal - $discount, 0);

        return SalesInvoiceDetail::create([
            'sales_invoice_id' => $invoice->id,
            'product_variant_id' => $variant->id,
            'product_quantity' => $quantity,
            'unit_price' => $unitPrice,
            'item_discount_type' => $item['item_discount_type'] ?? null,
            'item_discount_value' => $item['item_discount_value'] ?? null,
            'item_discount_amount' => $discount,
            'discount_amount' => $discount,
            'cost_price' => (float) ($variant->variant_cost ?: $variant->product?->product_cost ?: 0),
            'line_total' => $lineTotal,
            'line_total_after_discount' => $lineTotal,
        ]);
    }

    private function resolveItemDiscount(array $item, float $lineSubtotal): float
    {
        $type = $item['item_discount_type'] ?? null;
        $value = (float) ($item['item_discount_value'] ?? $item['discount_amount'] ?? 0);

        if ($type === null && isset($item['discount_amount'])) {
            $type = 'fixed';
        }

        return $this->discounts->calculateAmount($type, $value, $lineSubtotal);
    }

    private function assertStock(int $variantId, int $branchId, int $quantity): void
    {
        if ($this->inventory->available($variantId, $branchId) < $quantity) {
            throw ValidationException::withMessages(['items' => 'Selected quantity exceeds available stock.']);
        }
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $number = 'POS-'.now()->format('Ymd-His').'-'.random_int(100, 999);
        } while (SalesInvoice::where('invoice_number', $number)->exists());

        return $number;
    }
}
