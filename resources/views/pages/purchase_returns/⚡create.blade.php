<?php

use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Services\PurchaseReturnService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Create Purchase Return')] class extends Component {
    public ?int $supplier_id = null;
    public ?int $purchase_invoice_id = null;
    public string $return_date = '';
    public string $notes = '';
    public array $returnItems = [];
    public bool $confirming = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('purchase_returns.create'), 403);
        $this->return_date = now()->toDateString();

        if ($invoiceId = request()->integer('invoice_id')) {
            $invoice = PurchaseInvoice::query()->find($invoiceId);
            if ($invoice) {
                $this->supplier_id = (int) $invoice->supplier_id;
                $this->purchase_invoice_id = (int) $invoice->id;
                $this->hydrateReturnItems();
            }
        }
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->orderBy('supplier_name')->get(['id', 'supplier_name']);
    }

    #[Computed]
    public function invoices()
    {
        return PurchaseInvoice::query()
            ->with('supplier')
            ->when($this->supplier_id, fn ($query) => $query->where('supplier_id', $this->supplier_id))
            ->orderByDesc('purchase_invoice_date')
            ->get(['id', 'invoice_number', 'supplier_id', 'purchase_invoice_date', 'total_amount', 'remaining_amount']);
    }

    #[Computed]
    public function invoice(): ?PurchaseInvoice
    {
        if (! $this->purchase_invoice_id) {
            return null;
        }

        return PurchaseInvoice::query()
            ->with(['supplier', 'purchaseInvoiceDetails.productVariant.product', 'purchaseInvoiceDetails.productVariant.color', 'purchaseInvoiceDetails.productVariant.size', 'purchaseInvoiceDetails.purchaseReturnItems'])
            ->find($this->purchase_invoice_id);
    }

    #[Computed]
    public function returnTotal(): float
    {
        if (! $this->invoice) {
            return 0;
        }

        return $this->invoice->purchaseInvoiceDetails->sum(function ($detail): float {
            $quantity = (int) data_get($this->returnItems, "{$detail->id}.quantity", 0);

            return $quantity * (float) $detail->unit_cost;
        });
    }

    #[Computed]
    public function creditAmount(): float
    {
        if (! $this->invoice) {
            return 0;
        }

        return max($this->returnTotal - (float) $this->invoice->remaining_amount, 0);
    }

    public function updatedSupplierId(): void
    {
        $this->purchase_invoice_id = null;
        $this->returnItems = [];
    }

    public function updatedPurchaseInvoiceId(): void
    {
        if ($this->invoice) {
            $this->supplier_id = (int) $this->invoice->supplier_id;
        }

        $this->hydrateReturnItems();
    }

    public function confirm(): void
    {
        $this->validateReturn();
        $this->confirming = true;
    }

    public function save(PurchaseReturnService $service): void
    {
        $this->validateReturn();

        try {
            $purchaseReturn = $service->create([
                'purchase_invoice_id' => $this->purchase_invoice_id,
                'return_date' => $this->return_date,
                'notes' => $this->notes ?: null,
                'created_by' => auth()->id(),
            ], $this->normalizedItems());
        } catch (ValidationException $exception) {
            throw $exception;
        }

        session()->flash('success', 'Purchase return created successfully.');
        $this->redirectRoute('purchase-returns.show', $purchaseReturn, navigate: true);
    }

    private function hydrateReturnItems(): void
    {
        $this->returnItems = [];

        if (! $this->invoice) {
            return;
        }

        foreach ($this->invoice->purchaseInvoiceDetails as $detail) {
            $this->returnItems[$detail->id] = [
                'quantity' => 0,
                'reason' => '',
            ];
        }
    }

    private function validateReturn(): void
    {
        $this->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_invoice_id' => ['required', 'exists:purchase_invoices,id'],
            'return_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'returnItems' => ['array'],
        ]);

        if (! $this->invoice || (int) $this->invoice->supplier_id !== (int) $this->supplier_id) {
            throw ValidationException::withMessages(['purchase_invoice_id' => 'Selected invoice does not belong to this supplier.']);
        }

        $hasQuantity = false;
        foreach ($this->invoice->purchaseInvoiceDetails as $detail) {
            $quantity = (int) data_get($this->returnItems, "{$detail->id}.quantity", 0);
            $available = max((int) $detail->product_quantity - (int) $detail->purchaseReturnItems->sum('quantity'), 0);

            if ($quantity > 0) {
                $hasQuantity = true;
            }

            if ($quantity < 0 || $quantity > $available) {
                throw ValidationException::withMessages(["returnItems.{$detail->id}.quantity" => "Quantity must be between 0 and {$available}."]);
            }
        }

        if (! $hasQuantity) {
            throw ValidationException::withMessages(['returnItems' => 'Select at least one item quantity to return.']);
        }
    }

    private function normalizedItems(): array
    {
        return collect($this->returnItems)
            ->map(fn ($item, $detailId) => [
                'purchase_invoice_item_id' => (int) $detailId,
                'quantity' => (int) ($item['quantity'] ?? 0),
                'reason' => $item['reason'] ?? null,
            ])
            ->values()
            ->all();
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('purchase-returns.index') }}" wire:navigate class="text-sm font-semibold text-emerald-600">Back to purchase returns</a>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">Create Purchase Return</h1>
                <p class="mt-1 text-sm text-slate-500">This creates financial and inventory records. It cannot be edited or deleted later.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-4">
            <section class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-3">
                <div class="grid gap-4 md:grid-cols-3">
                    <label class="space-y-1">
                        <span class="text-sm font-semibold">Supplier</span>
                        <select wire:model.live="supplier_id" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                            <option value="">Select supplier</option>
                            @foreach ($this->suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-sm font-semibold">Purchase Invoice</span>
                        <select wire:model.live="purchase_invoice_id" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                            <option value="">Select invoice</option>
                            @foreach ($this->invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} - {{ $invoice->purchase_invoice_date?->format('Y-m-d') }}</option>
                            @endforeach
                        </select>
                        @error('purchase_invoice_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-sm font-semibold">Return Date</span>
                        <input type="date" wire:model="return_date" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @error('return_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200 dark:border-zinc-800">
                    <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                        <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                            <tr><th class="px-4 py-3 text-left">Product</th><th class="px-4 py-3 text-left">Purchased</th><th class="px-4 py-3 text-left">Returned</th><th class="px-4 py-3 text-left">Available</th><th class="px-4 py-3 text-left">Return Qty</th><th class="px-4 py-3 text-left">Unit Cost</th><th class="px-4 py-3 text-left">Reason</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                            @forelse ($this->invoice?->purchaseInvoiceDetails ?? [] as $detail)
                                @php($returned = (int) $detail->purchaseReturnItems->sum('quantity'))
                                @php($available = max((int) $detail->product_quantity - $returned, 0))
                                <tr>
                                    <td class="px-4 py-3 font-semibold">{{ $detail->productVariant?->product?->product_name ?? '-' }}<div class="text-xs font-normal text-slate-500">{{ $detail->productVariant?->sku }}</div></td>
                                    <td class="px-4 py-3">{{ $detail->product_quantity }}</td>
                                    <td class="px-4 py-3">{{ $returned }}</td>
                                    <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold dark:bg-zinc-800">{{ $available }}</span></td>
                                    <td class="px-4 py-3">
                                        <input type="number" min="0" max="{{ $available }}" wire:model.live="returnItems.{{ $detail->id }}.quantity" class="w-24 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                                        @error("returnItems.{$detail->id}.quantity") <div class="mt-1 text-xs text-red-600">{{ $message }}</div> @enderror
                                    </td>
                                    <td class="px-4 py-3">{{ number_format((float) $detail->unit_cost, 2) }}</td>
                                    <td class="px-4 py-3"><input wire:model="returnItems.{{ $detail->id }}.reason" class="w-48 rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950" placeholder="Optional"></td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-4 py-12 text-center text-slate-500">Select a purchase invoice to show returnable items.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @error('returnItems') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror

                <label class="mt-5 block space-y-1">
                    <span class="text-sm font-semibold">Notes</span>
                    <textarea wire:model="notes" rows="4" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></textarea>
                </label>

                <div class="mt-5 flex justify-end gap-2">
                    <a href="{{ route('purchase-returns.index') }}" wire:navigate class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</a>
                    <button type="button" wire:click="confirm" wire:loading.attr="disabled" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Review Return</button>
                </div>
            </section>

            <aside class="space-y-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm text-slate-500">Return Amount</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-600">{{ number_format($this->returnTotal, 2) }}</p>
                    <div class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between"><span class="text-slate-500">Invoice Remaining</span><strong>{{ number_format((float) ($this->invoice?->remaining_amount ?? 0), 2) }}</strong></div>
                        <div class="flex justify-between"><span class="text-slate-500">Supplier Credit</span><strong>{{ number_format($this->creditAmount, 2) }}</strong></div>
                    </div>
                </div>
            </aside>
        </div>
    </div>

    @if ($confirming)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <h2 class="text-lg font-bold">Confirm purchase return</h2>
                <p class="mt-2 text-sm text-slate-500">Stock will be deducted and supplier payable will be reduced immediately.</p>
                <div class="mt-4 rounded-lg bg-slate-50 p-4 text-sm dark:bg-zinc-800">
                    <div class="flex justify-between"><span>Return amount</span><strong>{{ number_format($this->returnTotal, 2) }}</strong></div>
                    <div class="mt-2 flex justify-between"><span>Supplier credit</span><strong>{{ number_format($this->creditAmount, 2) }}</strong></div>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="$set('confirming', false)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</button>
                    <button type="button" wire:click="save" wire:loading.attr="disabled" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create Return</button>
                </div>
            </div>
        </div>
    @endif
</div>
