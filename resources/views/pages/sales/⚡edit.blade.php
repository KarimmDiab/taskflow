<?php

use App\Models\Branches;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\ProductVariant;
use App\Models\SalesInvoice;
use App\Services\SalesInvoiceService;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Edit Sales Invoice')] class extends Component {
    public SalesInvoice $invoice;
    public ?int $customerId = null;
    public ?int $branchId = null;
    public ?int $paymentMethodId = null;
    public float $invoiceDiscount = 0;
    public float $taxAmount = 0;
    public float $paidAmount = 0;
    public array $items = [];

    public function mount(SalesInvoice $invoice): void
    {
        abort_unless(auth()->user()?->can('sales.edit'), 403);
        abort_if($invoice->status === 'cancelled', 403, 'Cancelled invoices cannot be edited.');

        $this->invoice = $invoice->load('salesInvoiceDetails.productVariant.product');
        $this->customerId = $invoice->customer_id;
        $this->branchId = $invoice->branch_id;
        $this->paymentMethodId = $invoice->payment_method_id;
        $this->taxAmount = (float) $invoice->tax_amount;
        $this->paidAmount = (float) $invoice->paid_amount;
        $this->items = $invoice->salesInvoiceDetails->map(fn ($detail): array => [
            'product_variant_id' => $detail->product_variant_id,
            'quantity' => (int) $detail->product_quantity,
            'unit_price' => (float) $detail->unit_price,
            'discount_amount' => (float) $detail->discount_amount,
        ])->values()->all();
        $this->invoiceDiscount = max((float) $invoice->deduction - collect($this->items)->sum('discount_amount'), 0);
    }

    public function getCustomersProperty()
    {
        return Customer::query()->orderBy('customer_name')->get();
    }

    public function getBranchesProperty()
    {
        return Branches::query()->orderBy('branch_name')->get();
    }

    public function getPaymentMethodsProperty()
    {
        return PaymentMethod::query()->orderBy('payment_method_name')->get();
    }

    public function getVariantsProperty()
    {
        return ProductVariant::query()->with(['product', 'color', 'size'])->where('is_active', true)->orderBy('sku')->get();
    }

    public function addItem(): void
    {
        $variant = $this->variants->first();
        if (! $variant) {
            return;
        }

        $this->items[] = [
            'product_variant_id' => $variant->id,
            'quantity' => 1,
            'unit_price' => (float) ($variant->variant_price ?: $variant->product?->product_price ?: 0),
            'discount_amount' => 0,
        ];
    }

    public function removeItem(int $index): void
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function save(SalesInvoiceService $service): void
    {
        $this->validate([
            'customerId' => ['required', 'exists:customers,id'],
            'branchId' => ['required', 'exists:branches,id'],
            'paymentMethodId' => ['required', 'exists:payment_methods,id'],
            'invoiceDiscount' => ['numeric', 'min:0'],
            'taxAmount' => ['numeric', 'min:0'],
            'paidAmount' => ['numeric', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_variant_id' => ['required', 'exists:product_variants,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
            'items.*.discount_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $service->update($this->invoice, [
            'customer_id' => $this->customerId,
            'branch_id' => $this->branchId,
            'payment_method_id' => $this->paymentMethodId,
            'discount_type' => $this->invoiceDiscount > 0 ? 'fixed' : null,
            'deduction' => $this->invoiceDiscount,
            'tax_amount' => $this->taxAmount,
            'paid_amount' => $this->paidAmount,
        ], $this->items);

        session()->flash('success', 'Sales invoice updated successfully.');
        $this->redirectRoute('sales.show', $this->invoice, navigate: true);
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->items)->sum(fn ($item) => max(((float) $item['quantity'] * (float) $item['unit_price']) - (float) ($item['discount_amount'] ?? 0), 0));
    }

    public function getGrandTotalProperty(): float
    {
        return max($this->subtotal - $this->invoiceDiscount + $this->taxAmount, 0);
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('sales.show', $invoice) }}" wire:navigate class="text-sm font-semibold text-blue-600 dark:text-blue-400">Back to invoice</a>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">Edit {{ $invoice->invoice_number }}</h1>
            </div>
            <button type="button" wire:click="save" wire:loading.attr="disabled" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">Save Changes</button>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-4 md:grid-cols-3">
                <select wire:model="customerId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">Select customer</option>
                    @foreach ($this->customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>
                <select wire:model="branchId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">Select branch</option>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                <select wire:model="paymentMethodId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">Payment method</option>
                    @foreach ($this->paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->payment_method_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4 dark:border-zinc-800">
                <h2 class="font-bold">Invoice Items</h2>
                <button type="button" wire:click="addItem" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Add Item</button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-left">Qty</th><th class="px-4 py-3 text-left">Sale Price</th><th class="px-4 py-3 text-left">Discount</th><th class="px-4 py-3"></th></tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @foreach ($items as $index => $item)
                            <tr>
                                <td class="px-4 py-3">
                                    <select wire:model="items.{{ $index }}.product_variant_id" class="w-72 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                                        @foreach ($this->variants as $variant)
                                            <option value="{{ $variant->id }}">{{ $variant->product?->product_name }} · {{ $variant->sku }} · {{ $variant->color?->color_name }} {{ $variant->size?->size_name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-4 py-3"><input type="number" min="1" wire:model.live="items.{{ $index }}.quantity" class="w-24 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></td>
                                <td class="px-4 py-3"><input type="number" min="0" step="0.01" wire:model.live="items.{{ $index }}.unit_price" class="w-28 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></td>
                                <td class="px-4 py-3"><input type="number" min="0" step="0.01" wire:model.live="items.{{ $index }}.discount_amount" class="w-28 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></td>
                                <td class="px-4 py-3 text-right"><button type="button" wire:click="removeItem({{ $index }})" class="rounded-md px-2 py-1 text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30">Remove</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Payment</h2>
                <div class="mt-4 grid gap-3">
                    <label class="text-sm">Invoice Discount<input type="number" min="0" step="0.01" wire:model.live="invoiceDiscount" class="mt-1 w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></label>
                    <label class="text-sm">Tax<input type="number" min="0" step="0.01" wire:model.live="taxAmount" class="mt-1 w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></label>
                    <label class="text-sm">Paid Amount<input type="number" min="0" step="0.01" wire:model.live="paidAmount" class="mt-1 w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></label>
                </div>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Totals</h2>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt>Subtotal</dt><dd>{{ number_format($this->subtotal, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Discount</dt><dd>{{ number_format($invoiceDiscount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Tax</dt><dd>{{ number_format($taxAmount, 2) }}</dd></div>
                    <div class="flex justify-between border-t pt-2 font-bold dark:border-zinc-800"><dt>Grand Total</dt><dd>{{ number_format($this->grandTotal, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Paid</dt><dd>{{ number_format($paidAmount, 2) }}</dd></div>
                    <div class="flex justify-between"><dt>Remaining</dt><dd>{{ number_format(max($this->grandTotal - $paidAmount, 0), 2) }}</dd></div>
                </dl>
            </div>
        </div>

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700 dark:border-red-900 dark:bg-red-950/40 dark:text-red-300">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</div>
