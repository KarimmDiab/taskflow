<?php

use App\Models\SupplierPayment;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Supplier Payment Details')] class extends Component {
    public SupplierPayment $supplierPayment;

    public function mount(SupplierPayment $supplierPayment): void
    {
        abort_unless(auth()->user()?->can('supplier_payments.view'), 403);
        $this->supplierPayment = $supplierPayment->load(['supplier', 'purchaseInvoice', 'paymentMethod', 'createdBy']);
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-5xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('supplier-payments.index') }}" wire:navigate class="text-sm font-semibold text-emerald-600">Back to payments</a>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">{{ $supplierPayment->payment_number }}</h1>
                <p class="mt-1 text-sm text-slate-500">Immutable supplier payment voucher.</p>
            </div>
            @can('supplier_payments.print')
                <a href="{{ route('supplier-payments.print', $supplierPayment) }}" target="_blank" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900">Print Voucher</a>
            @endcan
        </div>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 md:col-span-2">
                <h2 class="font-bold">Payment Information</h2>
                <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
                    <div class="flex justify-between gap-4 rounded-lg bg-slate-50 p-3 dark:bg-zinc-800"><dt class="text-slate-500">Supplier</dt><dd class="font-semibold">{{ $supplierPayment->supplier?->supplier_name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 rounded-lg bg-slate-50 p-3 dark:bg-zinc-800"><dt class="text-slate-500">Invoice</dt><dd>{{ $supplierPayment->purchaseInvoice?->invoice_number ?? 'Unallocated' }}</dd></div>
                    <div class="flex justify-between gap-4 rounded-lg bg-slate-50 p-3 dark:bg-zinc-800"><dt class="text-slate-500">Payment Method</dt><dd>{{ $supplierPayment->paymentMethod?->payment_method_name ?? '-' }}</dd></div>
                    <div class="flex justify-between gap-4 rounded-lg bg-slate-50 p-3 dark:bg-zinc-800"><dt class="text-slate-500">Payment Date</dt><dd>{{ $supplierPayment->payment_date?->format('Y-m-d') }}</dd></div>
                    <div class="flex justify-between gap-4 rounded-lg bg-slate-50 p-3 dark:bg-zinc-800"><dt class="text-slate-500">Reference</dt><dd>{{ $supplierPayment->reference_number ?: '-' }}</dd></div>
                    <div class="flex justify-between gap-4 rounded-lg bg-slate-50 p-3 dark:bg-zinc-800"><dt class="text-slate-500">Created By</dt><dd>{{ $supplierPayment->createdBy?->name ?? '-' }}</dd></div>
                </dl>
                <div class="mt-4 rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-800">
                    <p class="font-semibold">Notes</p>
                    <p class="mt-1 text-slate-500">{{ $supplierPayment->notes ?: 'No notes recorded.' }}</p>
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm font-medium text-slate-500">Amount Paid</p>
                <p class="mt-3 text-3xl font-bold text-emerald-600">{{ number_format((float) $supplierPayment->amount, 2) }}</p>
                @if ($supplierPayment->purchaseInvoice)
                    <div class="mt-5 space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Invoice Total</span><strong>{{ number_format((float) $supplierPayment->purchaseInvoice->total_amount, 2) }}</strong></div>
                        <div class="flex justify-between"><span class="text-slate-500">Total Paid</span><strong>{{ number_format((float) $supplierPayment->purchaseInvoice->paid_amount, 2) }}</strong></div>
                        <div class="flex justify-between"><span class="text-slate-500">Remaining</span><strong>{{ number_format((float) $supplierPayment->purchaseInvoice->remaining_amount, 2) }}</strong></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
