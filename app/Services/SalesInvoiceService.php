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
        private readonly SalesActivityLogger $logger,
    ) {}

    public function create(array $data, array $items): SalesInvoice
    {
        return DB::transaction(function () use ($data, $items): SalesInvoice {
            $totals = $this->calculateTotals($items, (float) ($data['discount_amount'] ?? $data['deduction'] ?? 0), (float) ($data['tax_amount'] ?? 0));
            $paid = (float) ($data['paid_amount'] ?? 0);

            if ($paid > $totals['grand_total']) {
                throw ValidationException::withMessages(['paid_amount' => 'Paid amount cannot exceed grand total.']);
            }

            $invoice = SalesInvoice::create([
                'invoice_number' => $data['invoice_number'] ?? $this->generateInvoiceNumber(),
                'total_amount' => $totals['subtotal'],
                'deduction' => $totals['discount'],
                'tax_amount' => $totals['tax'],
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
                $this->createDetail($invoice, $item);
                $this->inventory->adjust((int) $item['product_variant_id'], (int) $data['branch_id'], -((int) $item['quantity']));
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
            $invoice->load('salesInvoiceDetails');
            $oldBranchId = (int) $invoice->branch_id;
            $newBranchId = (int) $data['branch_id'];

            foreach ($invoice->salesInvoiceDetails as $detail) {
                $this->inventory->adjust((int) $detail->product_variant_id, $oldBranchId, (int) $detail->product_quantity);
            }

            $totals = $this->calculateTotals($items, (float) ($data['discount_amount'] ?? $data['deduction'] ?? 0), (float) ($data['tax_amount'] ?? 0));
            $paid = (float) ($data['paid_amount'] ?? 0);

            if ($paid > $totals['grand_total']) {
                throw ValidationException::withMessages(['paid_amount' => 'Paid amount cannot exceed grand total.']);
            }

            foreach ($items as $item) {
                $this->assertStock((int) $item['product_variant_id'], $newBranchId, (int) $item['quantity']);
            }

            $invoice->update([
                'total_amount' => $totals['subtotal'],
                'deduction' => $totals['discount'],
                'tax_amount' => $totals['tax'],
                'net_total' => $totals['grand_total'],
                'paid_amount' => $paid,
                'remaining_amount' => max($totals['grand_total'] - $paid, 0),
                'customer_id' => $data['customer_id'],
                'payment_method_id' => $data['payment_method_id'],
                'branch_id' => $newBranchId,
            ]);

            $invoice->salesInvoiceDetails()->delete();

            foreach ($items as $item) {
                $this->createDetail($invoice, $item);
                $this->inventory->adjust((int) $item['product_variant_id'], $newBranchId, -((int) $item['quantity']));
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
                $this->inventory->adjust((int) $detail->product_variant_id, (int) $invoice->branch_id, (int) $detail->product_quantity);
            }

            $invoice->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => auth()->id(),
                'cancellation_reason' => $reason,
            ]);

            $this->logger->invoice($invoice, 'cancelled', $reason);

            return $invoice->fresh();
        });
    }

    public function calculateTotals(array $items, float $invoiceDiscount = 0, float $tax = 0): array
    {
        $subtotal = collect($items)->sum(fn (array $item): float => ((float) $item['quantity'] * (float) $item['unit_price']) - (float) ($item['discount_amount'] ?? 0));
        $discount = min(max($invoiceDiscount, 0), $subtotal);
        $tax = max($tax, 0);
        $grandTotal = max($subtotal - $discount + $tax, 0);

        return [
            'subtotal' => round($subtotal, 2),
            'discount' => round($discount, 2),
            'tax' => round($tax, 2),
            'grand_total' => round($grandTotal, 2),
        ];
    }

    private function createDetail(SalesInvoice $invoice, array $item): SalesInvoiceDetail
    {
        $variant = ProductVariant::query()->with('product')->findOrFail((int) $item['product_variant_id']);
        $quantity = (int) $item['quantity'];
        $unitPrice = (float) $item['unit_price'];
        $discount = (float) ($item['discount_amount'] ?? 0);

        return SalesInvoiceDetail::create([
            'sales_invoice_id' => $invoice->id,
            'product_variant_id' => $variant->id,
            'product_quantity' => $quantity,
            'unit_price' => $unitPrice,
            'discount_amount' => $discount,
            'cost_price' => (float) ($variant->variant_cost ?: $variant->product?->product_cost ?: 0),
            'line_total' => max(($quantity * $unitPrice) - $discount, 0),
        ]);
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
