<?php

use App\Models\SalesInvoice;
use App\Services\SalesReturnService;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Sales Invoice Details')] class extends Component {
    public SalesInvoice $invoice;
    public bool $showReturnModal = false;
    public string $returnType = 'partial';
    public string $refundMethod = 'cash_refund';
    public string $reason = '';
    public array $returnItems = [];

    public function mount(SalesInvoice $invoice): void
    {
        abort_unless(auth()->user()?->can('sales.view'), 403);
        $this->invoice = $invoice->load([
            'customer',
            'branch',
            'user',
            'paymentMethod',
            'salesInvoiceDetails.productVariant.product',
            'salesInvoiceDetails.productVariant.color',
            'salesInvoiceDetails.productVariant.size',
            'salesInvoiceDetails.returnItems.salesReturn',
            'returns.items.productVariant.product',
            'returns.createdBy',
            'returns.approvedBy',
            'activityLogs.user',
        ]);

        foreach ($this->invoice->salesInvoiceDetails as $detail) {
            $this->returnItems[$detail->id] = 0;
        }

        $this->showReturnModal = request()->boolean('return');
    }

    public function createReturn(SalesReturnService $service): void
    {
        abort_unless(auth()->user()?->can('sales.return.create'), 403);

        $this->validate([
            'returnType' => ['required', 'in:full,partial,exchange'],
            'refundMethod' => ['required', 'in:cash_refund,store_credit,product_exchange,no_refund'],
            'reason' => ['required', 'string', 'min:3'],
            'returnItems' => ['array'],
        ]);

        $items = collect($this->returnItems)
            ->map(fn ($quantity, $detailId): array => [
                'sales_invoice_detail_id' => (int) $detailId,
                'quantity' => (int) $quantity,
            ])
            ->values()
            ->all();

        $service->create($this->invoice, [
            'return_type' => $this->returnType,
            'refund_method' => $this->refundMethod,
            'reason' => $this->reason,
        ], $items);

        session()->flash('success', 'Return request created and is pending manager approval.');
        $this->redirectRoute('sales.show', $this->invoice, navigate: true);
    }

    public function updatedReturnType(string $value): void
    {
        if ($value !== 'full') {
            return;
        }

        foreach ($this->invoice->salesInvoiceDetails as $detail) {
            $this->returnItems[$detail->id] = max((int) $detail->product_quantity - $detail->returned_quantity, 0);
        }
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-300">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('sales.index') }}" wire:navigate class="text-sm font-semibold text-blue-600 dark:text-blue-400">Back to sales</a>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">{{ $invoice->invoice_number }}</h1>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('sales.print')
                    <a href="{{ route('sales.print.receipt', $invoice) }}" target="_blank" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-white dark:border-zinc-700">POS Receipt</a>
                    <a href="{{ route('sales.print.a4', $invoice) }}" target="_blank" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-white dark:border-zinc-700">A4 Print</a>
                @endcan
                @if ($invoice->status !== 'cancelled')
                    @can('sales.edit')
                        <a href="{{ route('sales.edit', $invoice) }}" wire:navigate class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-white dark:border-zinc-700">Edit</a>
                    @endcan
                    @can('sales.return.create')
                        <button type="button" wire:click="$set('showReturnModal', true)" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Create Return</button>
                    @endcan
                @endif
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Invoice Information</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Date</dt><dd>{{ $invoice->created_at?->format('Y-m-d H:i') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Branch</dt><dd>{{ $invoice->branch?->branch_name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Cashier</dt><dd>{{ $invoice->user?->name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Status</dt><dd><span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300">{{ ucfirst($invoice->status) }}</span></dd></div>
                </dl>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Customer Information</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Name</dt><dd>{{ $invoice->customer?->customer_name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Contact</dt><dd>{{ $invoice->customer?->contact_info ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Payment Method</dt><dd>{{ $invoice->paymentMethod?->payment_method_name ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Payment Status</dt><dd>{{ ucfirst($invoice->payment_status) }}</dd></div>
                </dl>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Payment Summary</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-slate-500">Grand Total</dt><dd class="font-semibold">{{ number_format((float) $invoice->net_total, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Paid</dt><dd>{{ number_format((float) $invoice->paid_amount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-slate-500">Remaining</dt><dd>{{ number_format((float) $invoice->remaining_amount, 2) }}</dd></div>
                </dl>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="border-b border-slate-100 px-5 py-4 dark:border-zinc-800"><h2 class="font-bold">Products</h2></div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Product', 'SKU', 'Color', 'Size', 'Quantity', 'Sale Price', 'Discount', 'Line Total'] as $heading)
                                <th class="px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @foreach ($invoice->salesInvoiceDetails as $detail)
                            <tr>
                                <td class="px-4 py-3 font-semibold">{{ $detail->productVariant?->product?->product_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->productVariant?->sku ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->productVariant?->color?->color_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->productVariant?->size?->size_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $detail->product_quantity }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $detail->unit_price, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $detail->discount_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) ($detail->line_total ?: ($detail->product_quantity * $detail->unit_price)), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-3">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Totals</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt>Subtotal</dt><dd>{{ number_format((float) $invoice->total_amount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Discount</dt><dd>{{ number_format((float) $invoice->deduction, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Tax</dt><dd>{{ number_format((float) $invoice->tax_amount, 2) }}</dd></div>
                    <div class="flex justify-between border-t pt-2 font-bold dark:border-zinc-800"><dt>Grand Total</dt><dd>{{ number_format((float) $invoice->net_total, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Paid Amount</dt><dd>{{ number_format((float) $invoice->paid_amount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Remaining Amount</dt><dd>{{ number_format((float) $invoice->remaining_amount, 2) }}</dd></div>
                </dl>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
                <h2 class="font-bold">Return History</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($invoice->returns as $return)
                        <div class="rounded-lg border border-slate-200 p-3 text-sm dark:border-zinc-800">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div><span class="font-semibold">{{ $return->return_number }}</span> · {{ ucfirst($return->return_type) }} · {{ number_format((float) $return->return_amount, 2) }}</div>
                                <span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold dark:bg-zinc-800">{{ ucfirst($return->status) }}</span>
                            </div>
                            <p class="mt-1 text-slate-500">{{ $return->reason }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No returns have been created for this invoice.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <h2 class="font-bold">Activity Log</h2>
            <div class="mt-4 space-y-3">
                @forelse ($invoice->activityLogs->sortByDesc('created_at') as $log)
                    <div class="flex justify-between gap-4 rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-800/60">
                        <div><span class="font-semibold">{{ ucfirst($log->action) }}</span><p class="text-slate-500">{{ $log->description }}</p></div>
                        <div class="text-right text-xs text-slate-500">{{ $log->user?->name ?? 'System' }}<br>{{ $log->created_at?->format('Y-m-d H:i') }}</div>
                    </div>
                @empty
                    <p class="text-sm text-slate-500">No activity has been recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    @if ($showReturnModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
            <div class="max-h-[90vh] w-full max-w-3xl overflow-y-auto rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold">Create Return Request</h2>
                    <button type="button" wire:click="$set('showReturnModal', false)" class="rounded-lg border px-3 py-1 text-sm dark:border-zinc-700">Close</button>
                </div>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <select wire:model="returnType" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="partial">Partial Return</option>
                        <option value="full">Full Return</option>
                        <option value="exchange">Exchange</option>
                    </select>
                    <select wire:model="refundMethod" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        <option value="cash_refund">Cash Refund</option>
                        <option value="store_credit">Store Credit</option>
                        <option value="product_exchange">Product Exchange</option>
                        <option value="no_refund">No Refund</option>
                    </select>
                    <textarea wire:model="reason" rows="3" placeholder="Return reason" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950 sm:col-span-2"></textarea>
                </div>
                @error('items') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                @error('reason') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="text-left text-xs uppercase text-slate-500"><tr><th class="py-2">Product</th><th>Sold</th><th>Returned</th><th>Return Qty</th></tr></thead>
                        <tbody>
                            @foreach ($invoice->salesInvoiceDetails as $detail)
                                @php($max = max((int) $detail->product_quantity - $detail->returned_quantity, 0))
                                <tr class="border-t dark:border-zinc-800">
                                    <td class="py-2">{{ $detail->productVariant?->product?->product_name }}</td>
                                    <td>{{ $detail->product_quantity }}</td>
                                    <td>{{ $detail->returned_quantity }}</td>
                                    <td><input type="number" min="0" max="{{ $max }}" wire:model="returnItems.{{ $detail->id }}" class="w-24 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="$set('showReturnModal', false)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</button>
                    <button type="button" wire:click="createReturn" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Submit Return</button>
                </div>
            </div>
        </div>
    @endif
</div>
