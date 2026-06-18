<?php

namespace App\Services;

use App\Models\SalesInvoice;
use App\Models\SalesReturn;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SalesReturnService
{
    public function __construct(
        private readonly InventoryService $inventory,
        private readonly SalesActivityLogger $logger,
    ) {}

    public function create(SalesInvoice $invoice, array $data, array $items): SalesReturn
    {
        if ($invoice->status === 'cancelled') {
            throw ValidationException::withMessages(['invoice' => 'Cannot return a cancelled invoice.']);
        }

        return DB::transaction(function () use ($invoice, $data, $items): SalesReturn {
            $invoice->load('salesInvoiceDetails.returnItems.salesReturn');
            $validItems = collect($items)->filter(fn ($item) => (int) ($item['quantity'] ?? 0) > 0)->values();

            if ($validItems->isEmpty()) {
                throw ValidationException::withMessages(['items' => 'Select at least one item to return.']);
            }

            $return = SalesReturn::create([
                'return_number' => $this->generateReturnNumber(),
                'sales_invoice_id' => $invoice->id,
                'return_type' => $data['return_type'],
                'refund_method' => $data['refund_method'],
                'status' => 'pending',
                'reason' => $data['reason'],
                'created_by' => auth()->id(),
            ]);

            $subtotal = 0;
            $discount = 0;

            foreach ($validItems as $item) {
                $detail = $invoice->salesInvoiceDetails->firstWhere('id', (int) $item['sales_invoice_detail_id']);

                if (! $detail) {
                    throw ValidationException::withMessages(['items' => 'Invalid invoice item selected.']);
                }

                $remainingReturnable = (int) $detail->product_quantity - $detail->returned_quantity;
                $quantity = (int) $item['quantity'];

                if ($quantity > $remainingReturnable) {
                    throw ValidationException::withMessages(['items' => 'Returned quantity cannot exceed sold quantity.']);
                }

                $lineDiscount = round(((float) $detail->discount_amount / max((int) $detail->product_quantity, 1)) * $quantity, 2);
                $lineTotal = max(($quantity * (float) $detail->unit_price) - $lineDiscount, 0);
                $subtotal += $lineTotal;
                $discount += $lineDiscount;

                $return->items()->create([
                    'sales_invoice_detail_id' => $detail->id,
                    'product_variant_id' => $detail->product_variant_id,
                    'quantity' => $quantity,
                    'unit_price' => $detail->unit_price,
                    'discount_amount' => $lineDiscount,
                    'line_total' => $lineTotal,
                ]);
            }

            $return->update([
                'subtotal_amount' => round($subtotal, 2),
                'discount_amount' => round($discount, 2),
                'return_amount' => round($subtotal, 2),
            ]);

            $this->logger->salesReturn($return, 'created', 'Sales return request created.');

            return $return->fresh(['items.productVariant.product', 'invoice']);
        });
    }

    public function approve(SalesReturn $return, ?string $note = null): SalesReturn
    {
        if ($return->status !== 'pending') {
            throw ValidationException::withMessages(['return' => 'Only pending returns can be approved.']);
        }

        return DB::transaction(function () use ($return, $note): SalesReturn {
            $return->load('invoice', 'items');

            foreach ($return->items as $item) {
                $this->inventory->adjust((int) $item->product_variant_id, (int) $return->invoice->branch_id, (int) $item->quantity);
            }

            $return->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'approval_note' => $note,
            ]);

            $this->logger->salesReturn($return, 'approved', $note ?: 'Sales return approved.');

            return $return->fresh();
        });
    }

    public function reject(SalesReturn $return, string $note): SalesReturn
    {
        if ($return->status !== 'pending') {
            throw ValidationException::withMessages(['return' => 'Only pending returns can be rejected.']);
        }

        $return->update([
            'status' => 'rejected',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
            'approval_note' => $note,
        ]);

        $this->logger->salesReturn($return, 'rejected', $note);

        return $return->fresh();
    }

    public function complete(SalesReturn $return): SalesReturn
    {
        if ($return->status !== 'approved') {
            throw ValidationException::withMessages(['return' => 'Only approved returns can be completed.']);
        }

        $return->update(['status' => 'completed']);
        $this->logger->salesReturn($return, 'completed', 'Sales return completed.');

        return $return->fresh();
    }

    private function generateReturnNumber(): string
    {
        do {
            $number = 'RET-'.now()->format('Ymd-His').'-'.random_int(100, 999);
        } while (SalesReturn::where('return_number', $number)->exists());

        return $number;
    }
}
