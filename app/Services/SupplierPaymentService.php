<?php

namespace App\Services;

use App\Models\PurchaseInvoice;
use App\Models\SupplierPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupplierPaymentService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function record(array $data): SupplierPayment
    {
        return DB::transaction(function () use ($data): SupplierPayment {
            $invoice = null;
            $amount = round((float) $data['amount'], 2);

            if ($amount <= 0) {
                throw ValidationException::withMessages(['amount' => 'Payment amount must be greater than zero.']);
            }

            if (! empty($data['purchase_invoice_id'])) {
                $invoice = PurchaseInvoice::query()
                    ->whereKey($data['purchase_invoice_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $invoice->supplier_id !== (int) $data['supplier_id']) {
                    throw ValidationException::withMessages(['purchase_invoice_id' => 'Selected invoice does not belong to this supplier.']);
                }

                $remaining = round((float) $invoice->remaining_amount, 2);

                if ($amount > $remaining) {
                    throw ValidationException::withMessages(['amount' => 'Payment amount cannot exceed the remaining invoice balance.']);
                }
            }

            $payment = SupplierPayment::create([
                'payment_number' => $this->nextPaymentNumber(),
                'supplier_id' => $data['supplier_id'],
                'purchase_invoice_id' => $data['purchase_invoice_id'] ?? null,
                'payment_method_id' => $data['payment_method_id'],
                'amount' => $amount,
                'payment_date' => $data['payment_date'],
                'reference_number' => $data['reference_number'] ?? null,
                'notes' => $data['notes'] ?? null,
                'created_by' => $data['created_by'],
            ]);

            if ($invoice) {
                $paid = round((float) $invoice->supplierPayments()->sum('amount'), 2);
                $invoice->forceFill([
                    'paid_amount' => $paid,
                    'remaining_amount' => max(round((float) $invoice->total_amount - $paid, 2), 0),
                ])->save();
            }

            return $payment->load(['supplier', 'purchaseInvoice', 'paymentMethod', 'createdBy']);
        });
    }

    private function nextPaymentNumber(): string
    {
        $prefix = 'SP-'.now()->format('Y').'-';

        $latest = SupplierPayment::query()
            ->where('payment_number', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('payment_number')
            ->value('payment_number');

        $next = 1;
        if ($latest && preg_match('/^SP-\d{4}-(\d{6})$/', $latest, $matches)) {
            $next = (int) $matches[1] + 1;
        }

        return $prefix.str_pad((string) $next, 6, '0', STR_PAD_LEFT);
    }
}
