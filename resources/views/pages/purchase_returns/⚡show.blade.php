<?php

use App\Models\PurchaseReturn;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Purchase Return Details')] class extends Component {
    public PurchaseReturn $purchaseReturn;

    public function mount(PurchaseReturn $purchaseReturn): void
    {
        abort_unless(auth()->user()?->can('purchase_returns.view'), 403);

        $this->purchaseReturn = $purchaseReturn->load([
            'supplier',
            'purchaseInvoice',
            'items.productVariant.product',
            'items.productVariant.color',
            'items.productVariant.size',
            'items.purchaseInvoiceItem',
            'stockMovements.branch',
            'stockMovements.productVariant.product',
            'createdBy',
            'supplierCredit',
        ]);
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('purchase-returns.index') }}" wire:navigate class="text-sm font-semibold text-emerald-600">Back to purchase returns</a>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">{{ $purchaseReturn->return_number }}</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $purchaseReturn->return_date?->format('Y-m-d') }} · {{ $purchaseReturn->supplier?->supplier_name ?? '-' }}</p>
            </div>
            @can('purchase_returns.print')
                <a href="{{ route('purchase-returns.print', $purchaseReturn) }}" target="_blank" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800 dark:bg-white dark:text-slate-900">Print</a>
            @endcan
        </div>

        <div class="grid gap-4 lg:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Return Amount</p><p class="mt-2 text-2xl font-bold text-emerald-600">{{ number_format((float) $purchaseReturn->total_amount, 2) }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Supplier Credit</p><p class="mt-2 text-2xl font-bold text-amber-600">{{ number_format((float) ($purchaseReturn->supplierCredit?->amount ?? 0), 2) }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Original Invoice</p><p class="mt-2 font-bold">{{ $purchaseReturn->purchaseInvoice?->invoice_number ?? '-' }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Created By</p><p class="mt-2 font-bold">{{ $purchaseReturn->createdBy?->name ?? '-' }}</p></div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-zinc-800"><h2 class="font-bold">Items Returned</h2></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400"><tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-left">Quantity</th><th class="px-4 py-3 text-left">Unit Cost</th><th class="px-4 py-3 text-left">Return Amount</th><th class="px-4 py-3 text-left">Reason</th></tr></thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @foreach ($purchaseReturn->items as $item)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $item->productVariant?->product?->product_name ?? '-' }}<div class="text-xs font-normal text-slate-500">{{ $item->productVariant?->sku }}</div></td>
                                <td class="px-4 py-3">{{ $item->quantity }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $item->unit_cost, 2) }}</td>
                                <td class="px-4 py-3 font-semibold">{{ number_format((float) $item->total, 2) }}</td>
                                <td class="px-4 py-3">{{ $item->reason ?: '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Notes</h2>
                <p class="mt-3 text-sm text-slate-500">{{ $purchaseReturn->notes ?: 'No notes recorded.' }}</p>
            </div>
            <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
                <div class="border-b border-slate-100 px-5 py-4 dark:border-zinc-800"><h2 class="font-bold">Related Stock Movements</h2></div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400"><tr><th class="px-4 py-3 text-left">Date</th><th class="px-4 py-3 text-left">Branch</th><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-left">Direction</th><th class="px-4 py-3 text-left">Qty</th><th class="px-4 py-3 text-left">After</th></tr></thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @foreach ($purchaseReturn->stockMovements as $movement)
                                <tr>
                                    <td class="px-4 py-3">{{ $movement->movement_date?->format('Y-m-d H:i') }}</td>
                                    <td class="px-4 py-3">{{ $movement->branch?->branch_name ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $movement->productVariant?->product?->product_name ?? '-' }}</td>
                                    <td class="px-4 py-3"><span class="rounded-full bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 dark:bg-red-950/40 dark:text-red-300">{{ $movement->direction }}</span></td>
                                    <td class="px-4 py-3">{{ $movement->quantity }}</td>
                                    <td class="px-4 py-3">{{ $movement->quantity_after }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
