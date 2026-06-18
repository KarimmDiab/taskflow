<?php

use App\Models\PurchaseInvoice;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Purchase Invoice Details')] class extends Component {
    public PurchaseInvoice $purchaseInvoice;

    public function mount(PurchaseInvoice $purchaseInvoice): void
    {
        abort_unless(auth()->user()?->can('purchase_invoices.view'), 403);

        $this->purchaseInvoice = $purchaseInvoice->load([
            'supplier',
            'user',
            'paymentMethod',
            'purchaseInvoiceDetails.productVariant.product',
            'purchaseInvoiceDetails.productVariant.color',
            'purchaseInvoiceDetails.productVariant.size',
            'purchaseInvoiceDetails.purchaseReturnItems',
            'supplierPayments.paymentMethod',
            'supplierPayments.createdBy',
            'purchaseReturns.items',
            'purchaseReturns.createdBy',
        ]);
    }

    public function statusLabel(): string
    {
        return match ($this->purchaseInvoice->payment_status) {
            'fully_paid' => 'Fully Paid',
            'partially_paid' => 'Partially Paid',
            default => 'Unpaid',
        };
    }

    public function statusClass(): string
    {
        return match ($this->purchaseInvoice->payment_status) {
            'fully_paid' => 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300',
            'partially_paid' => 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300',
            default => 'bg-red-50 text-red-700 dark:bg-red-950/40 dark:text-red-300',
        };
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('purchaseInvoices') }}" wire:navigate class="text-sm font-semibold text-emerald-600">Back to purchases</a>
                <div class="mt-1 flex flex-wrap items-center gap-3">
                    <h1 class="text-2xl font-bold tracking-tight">{{ $purchaseInvoice->invoice_number }}</h1>
                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $this->statusClass() }}">{{ $this->statusLabel() }}</span>
                </div>
                <p class="mt-1 text-sm text-slate-500">{{ $purchaseInvoice->purchase_invoice_date?->format('Y-m-d') }} · {{ $purchaseInvoice->supplier?->supplier_name ?? '-' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('supplier_payments.create')
                    @if ((float) $purchaseInvoice->remaining_amount > 0)
                        <a href="{{ route('supplier-payments.create', ['invoice_id' => $purchaseInvoice->id]) }}" wire:navigate class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Record Payment</a>
                    @endif
                @endcan
                @can('supplier_payments.view')
                    <a href="{{ route('supplier-ledger.index', ['supplier_id' => $purchaseInvoice->supplier_id]) }}" wire:navigate class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50 dark:border-zinc-800 dark:bg-zinc-900">Supplier Ledger</a>
                @endcan
                @can('purchase_returns.create')
                    <a href="{{ route('purchase-returns.create', ['invoice_id' => $purchaseInvoice->id]) }}" wire:navigate class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50 dark:border-zinc-800 dark:bg-zinc-900">Create Purchase Return</a>
                @endcan
            </div>
        </div>

        @php($totalReturned = (float) $purchaseInvoice->purchaseReturns->sum('total_amount'))
        @php($remainingReturnableQty = (int) $purchaseInvoice->purchaseInvoiceDetails->sum(fn ($detail) => max((int) $detail->product_quantity - (int) $detail->purchaseReturnItems->sum('quantity'), 0)))
        <div class="grid gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Invoice Total</p><p class="mt-2 text-2xl font-bold">{{ number_format((float) $purchaseInvoice->total_amount, 2) }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Total Paid</p><p class="mt-2 text-2xl font-bold text-emerald-600">{{ number_format((float) $purchaseInvoice->paid_amount, 2) }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Remaining Balance</p><p class="mt-2 text-2xl font-bold text-amber-600">{{ number_format((float) $purchaseInvoice->remaining_amount, 2) }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm text-slate-500">Payment Progress</p>
                @php($paidPct = (float) $purchaseInvoice->total_amount > 0 ? min((float) $purchaseInvoice->paid_amount / (float) $purchaseInvoice->total_amount * 100, 100) : 0)
                <p class="mt-2 text-2xl font-bold">{{ number_format($paidPct, 1) }}%</p>
                <div class="mt-3 h-2 rounded-full bg-slate-100 dark:bg-zinc-800"><div class="h-2 rounded-full bg-emerald-500" style="width: {{ $paidPct }}%"></div></div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Supplier Information</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Supplier</dt><dd class="font-semibold">{{ $purchaseInvoice->supplier?->supplier_name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Phone</dt><dd>{{ $purchaseInvoice->supplier?->supplier_phone ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Created By</dt><dd>{{ $purchaseInvoice->user?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Initial Method</dt><dd>{{ $purchaseInvoice->paymentMethod?->payment_method_name ?? '-' }}</dd></div>
                </dl>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
                <div class="border-b border-slate-100 px-5 py-4 dark:border-zinc-800"><h2 class="font-bold">Payment History</h2></div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400"><tr><th class="px-4 py-3 text-left">Payment</th><th class="px-4 py-3 text-left">Date</th><th class="px-4 py-3 text-left">Method</th><th class="px-4 py-3 text-left">Amount</th><th class="px-4 py-3 text-left">Created By</th><th class="px-4 py-3 text-left">Actions</th></tr></thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($purchaseInvoice->supplierPayments->sortByDesc('payment_date') as $payment)
                                <tr>
                                    <td class="px-4 py-3 font-mono font-semibold">{{ $payment->payment_number }}</td>
                                    <td class="px-4 py-3">{{ $payment->payment_date?->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $payment->paymentMethod?->payment_method_name ?? '-' }}</td>
                                    <td class="px-4 py-3 font-semibold text-emerald-600">{{ number_format((float) $payment->amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ $payment->createdBy?->name ?? '-' }}</td>
                                    <td class="px-4 py-3"><a href="{{ route('supplier-payments.show', $payment) }}" wire:navigate class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">No payments recorded for this invoice.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Return Summary</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Total Returned Amount</dt><dd class="font-semibold">{{ number_format($totalReturned, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Remaining Returnable Qty</dt><dd class="font-semibold">{{ $remainingReturnableQty }}</dd></div>
                </dl>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
                <div class="border-b border-slate-100 px-5 py-4 dark:border-zinc-800"><h2 class="font-bold">Return History</h2></div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400"><tr><th class="px-4 py-3 text-left">Return</th><th class="px-4 py-3 text-left">Date</th><th class="px-4 py-3 text-left">Items</th><th class="px-4 py-3 text-left">Amount</th><th class="px-4 py-3 text-left">Created By</th><th class="px-4 py-3 text-left">Actions</th></tr></thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($purchaseInvoice->purchaseReturns->sortByDesc('return_date') as $return)
                                <tr>
                                    <td class="px-4 py-3 font-mono font-semibold">{{ $return->return_number }}</td>
                                    <td class="px-4 py-3">{{ $return->return_date?->format('Y-m-d') }}</td>
                                    <td class="px-4 py-3">{{ $return->items->sum('quantity') }}</td>
                                    <td class="px-4 py-3 font-semibold text-emerald-600">{{ number_format((float) $return->total_amount, 2) }}</td>
                                    <td class="px-4 py-3">{{ $return->createdBy?->name ?? '-' }}</td>
                                    <td class="px-4 py-3"><a href="{{ route('purchase-returns.show', $return) }}" wire:navigate class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-500">No purchase returns have been created for this invoice.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-zinc-800"><h2 class="font-bold">Invoice Items</h2></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400"><tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-left">SKU</th><th class="px-4 py-3 text-left">Qty</th><th class="px-4 py-3 text-left">Unit Cost</th><th class="px-4 py-3 text-left">Line Total</th></tr></thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @foreach ($purchaseInvoice->purchaseInvoiceDetails as $detail)
                            @php($returnedQty = (int) $detail->purchaseReturnItems->sum('quantity'))
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $detail->productVariant?->product?->product_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->productVariant?->sku ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->product_quantity }} <span class="text-xs text-slate-500">({{ max((int) $detail->product_quantity - $returnedQty, 0) }} returnable)</span></td>
                                <td class="px-4 py-3">{{ number_format((float) $detail->unit_cost, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $detail->unit_cost * (float) $detail->product_quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
