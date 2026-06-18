<?php

use App\Models\PaymentMethod;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Services\SupplierPaymentService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Record Supplier Payment')] class extends Component {
    public ?int $supplier_id = null;
    public ?int $purchase_invoice_id = null;
    public ?int $payment_method_id = null;
    public string $amount = '';
    public string $payment_date = '';
    public string $reference_number = '';
    public string $notes = '';
    public bool $confirming = false;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('supplier_payments.create'), 403);

        $this->payment_date = now()->toDateString();

        if ($invoiceId = request()->integer('invoice_id')) {
            $invoice = PurchaseInvoice::query()->find($invoiceId);
            if ($invoice) {
                $this->supplier_id = (int) $invoice->supplier_id;
                $this->purchase_invoice_id = (int) $invoice->id;
                $this->amount = (string) max((float) $invoice->remaining_amount, 0);
            }
        }
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->orderBy('supplier_name')->get(['id', 'supplier_name']);
    }

    #[Computed]
    public function paymentMethods()
    {
        return PaymentMethod::query()->where('is_active', true)->orderBy('payment_method_name')->get(['id', 'payment_method_name']);
    }

    #[Computed]
    public function invoices()
    {
        return PurchaseInvoice::query()
            ->when($this->supplier_id, fn ($query) => $query->where('supplier_id', $this->supplier_id))
            ->where('remaining_amount', '>', 0)
            ->orderByDesc('purchase_invoice_date')
            ->get(['id', 'invoice_number', 'supplier_id', 'total_amount', 'paid_amount', 'remaining_amount']);
    }

    #[Computed]
    public function selectedInvoice(): ?PurchaseInvoice
    {
        if (! $this->purchase_invoice_id) {
            return null;
        }

        return PurchaseInvoice::query()->with('supplier')->find($this->purchase_invoice_id);
    }

    #[Computed]
    public function balanceAfterPayment(): float
    {
        if (! $this->selectedInvoice) {
            return 0;
        }

        return max((float) $this->selectedInvoice->remaining_amount - (float) $this->amount, 0);
    }

    public function updatedSupplierId(): void
    {
        $this->purchase_invoice_id = null;
        $this->amount = '';
    }

    public function updatedPurchaseInvoiceId(): void
    {
        if ($this->selectedInvoice) {
            $this->supplier_id = (int) $this->selectedInvoice->supplier_id;
            $this->amount = (string) $this->selectedInvoice->remaining_amount;
        }
    }

    public function confirm(): void
    {
        $this->validatePayment();
        $this->confirming = true;
    }

    public function save(SupplierPaymentService $service): void
    {
        $this->validatePayment();

        try {
            $payment = $service->record([
                'supplier_id' => $this->supplier_id,
                'purchase_invoice_id' => $this->purchase_invoice_id,
                'payment_method_id' => $this->payment_method_id,
                'amount' => $this->amount,
                'payment_date' => $this->payment_date,
                'reference_number' => $this->reference_number ?: null,
                'notes' => $this->notes ?: null,
                'created_by' => auth()->id(),
            ]);
        } catch (ValidationException $exception) {
            throw $exception;
        }

        session()->flash('success', 'Supplier payment recorded successfully.');
        $this->redirectRoute('supplier-payments.show', $payment, navigate: true);
    }

    private function validatePayment(): void
    {
        $this->validate([
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'purchase_invoice_id' => ['nullable', 'exists:purchase_invoices,id'],
            'payment_method_id' => ['required', 'exists:payment_methods,id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'payment_date' => ['required', 'date'],
            'reference_number' => ['nullable', 'string', 'max:191'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($this->selectedInvoice && (float) $this->amount > (float) $this->selectedInvoice->remaining_amount) {
            throw ValidationException::withMessages(['amount' => 'Payment amount cannot exceed the remaining invoice balance.']);
        }

        if ($this->selectedInvoice && (int) $this->selectedInvoice->supplier_id !== (int) $this->supplier_id) {
            throw ValidationException::withMessages(['purchase_invoice_id' => 'Selected invoice does not belong to this supplier.']);
        }
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-5xl space-y-6">
        @if (session('success'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">{{ session('success') }}</div>
        @endif

        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('supplier-payments.index') }}" wire:navigate class="text-sm font-semibold text-emerald-600">Back to supplier payments</a>
                <h1 class="mt-1 text-2xl font-bold tracking-tight">Record Supplier Payment</h1>
                <p class="mt-1 text-sm text-slate-500">Payments are immutable financial records. Corrections must be reversed later.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <form wire:submit.prevent="confirm" class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900 lg:col-span-2">
                <div class="grid gap-4 sm:grid-cols-2">
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
                            <option value="">Optional unallocated payment</option>
                            @foreach ($this->invoices as $invoice)
                                <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} - Remaining {{ number_format((float) $invoice->remaining_amount, 2) }}</option>
                            @endforeach
                        </select>
                        @error('purchase_invoice_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-sm font-semibold">Payment Method</span>
                        <select wire:model="payment_method_id" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                            <option value="">Select method</option>
                            @foreach ($this->paymentMethods as $method)
                                <option value="{{ $method->id }}">{{ $method->payment_method_name }}</option>
                            @endforeach
                        </select>
                        @error('payment_method_id') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-sm font-semibold">Payment Date</span>
                        <input type="date" wire:model="payment_date" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @error('payment_date') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-sm font-semibold">Amount</span>
                        <input type="number" min="0.01" step="0.01" wire:model.live="amount" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @error('amount') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1">
                        <span class="text-sm font-semibold">Reference Number</span>
                        <input wire:model="reference_number" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                        @error('reference_number') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                    <label class="space-y-1 sm:col-span-2">
                        <span class="text-sm font-semibold">Notes</span>
                        <textarea wire:model="notes" rows="4" class="w-full rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950"></textarea>
                        @error('notes') <span class="text-xs text-red-600">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <a href="{{ route('supplier-payments.index') }}" wire:navigate class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</a>
                    <button type="submit" wire:loading.attr="disabled" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Review Payment</button>
                </div>
            </form>

            <aside class="space-y-4">
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h2 class="font-bold">Invoice Balance</h2>
                    @if ($this->selectedInvoice)
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between"><dt class="text-slate-500">Invoice Total</dt><dd class="font-semibold">{{ number_format((float) $this->selectedInvoice->total_amount, 2) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-500">Total Paid</dt><dd>{{ number_format((float) $this->selectedInvoice->paid_amount, 2) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-slate-500">Remaining</dt><dd>{{ number_format((float) $this->selectedInvoice->remaining_amount, 2) }}</dd></div>
                            <div class="flex justify-between border-t border-slate-200 pt-3 font-bold dark:border-zinc-800"><dt>After Payment</dt><dd>{{ number_format($this->balanceAfterPayment, 2) }}</dd></div>
                        </dl>
                        @php($paidPct = (float) $this->selectedInvoice->total_amount > 0 ? min(((float) $this->selectedInvoice->paid_amount + (float) $amount) / (float) $this->selectedInvoice->total_amount * 100, 100) : 0)
                        <div class="mt-4 h-2 rounded-full bg-slate-100 dark:bg-zinc-800"><div class="h-2 rounded-full bg-emerald-500" style="width: {{ $paidPct }}%"></div></div>
                    @else
                        <p class="mt-3 text-sm text-slate-500">Choose an invoice to preview total, paid, and remaining balance. Unallocated supplier payments are allowed.</p>
                    @endif
                </div>
            </aside>
        </div>
    </div>

    @if ($confirming)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/60 p-4">
            <div class="w-full max-w-md rounded-xl bg-white p-5 shadow-2xl dark:bg-zinc-900">
                <h2 class="text-lg font-bold">Confirm supplier payment</h2>
                <p class="mt-2 text-sm text-slate-500">This payment cannot be edited or deleted after creation.</p>
                <div class="mt-4 rounded-lg bg-slate-50 p-4 text-sm dark:bg-zinc-800">
                    <div class="flex justify-between"><span>Amount</span><strong>{{ number_format((float) $amount, 2) }}</strong></div>
                    <div class="mt-2 flex justify-between"><span>Balance after payment</span><strong>{{ number_format($this->balanceAfterPayment, 2) }}</strong></div>
                </div>
                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" wire:click="$set('confirming', false)" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold dark:border-zinc-700">Cancel</button>
                    <button type="button" wire:click="save" wire:loading.attr="disabled" class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white hover:bg-emerald-700">Create Payment</button>
                </div>
            </div>
        </div>
    @endif
</div>
