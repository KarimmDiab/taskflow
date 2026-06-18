<?php

use App\Models\PaymentMethod;
use App\Models\PurchaseInvoice;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Supplier Payments')] class extends Component {
    use WithPagination;

    public string $supplierId = '';
    public string $invoiceNumber = '';
    public string $paymentMethodId = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $perPage = 15;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('supplier_payments.view'), 403);
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
    public function stats(): array
    {
        return [
            'balance' => (float) PurchaseInvoice::query()->sum('remaining_amount'),
            'paid' => (float) SupplierPayment::query()->sum('amount'),
            'outstanding' => (float) PurchaseInvoice::query()->where('remaining_amount', '>', 0)->sum('remaining_amount'),
            'month' => (float) SupplierPayment::query()->whereBetween('payment_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->sum('amount'),
        ];
    }

    #[Computed]
    public function payments()
    {
        return SupplierPayment::query()
            ->with(['supplier', 'purchaseInvoice', 'paymentMethod', 'createdBy'])
            ->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId))
            ->when($this->invoiceNumber !== '', fn ($query) => $query->whereHas('purchaseInvoice', fn ($invoice) => $invoice->where('invoice_number', 'like', '%' . $this->invoiceNumber . '%')))
            ->when($this->paymentMethodId !== '', fn ($query) => $query->where('payment_method_id', $this->paymentMethodId))
            ->when($this->dateFrom !== '', fn ($query) => $query->whereDate('payment_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($query) => $query->whereDate('payment_date', '<=', $this->dateTo))
            ->latest('payment_date')
            ->latest()
            ->paginate($this->perPage);
    }

    public function resetFilters(): void
    {
        $this->reset(['supplierId', 'invoiceNumber', 'paymentMethodId', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function updating($property): void
    {
        if (in_array($property, ['supplierId', 'invoiceNumber', 'paymentMethodId', 'dateFrom', 'dateTo', 'perPage'], true)) {
            $this->resetPage();
        }
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">Supplier accounts</p>
                <h1 class="text-2xl font-bold tracking-tight">Supplier Payments</h1>
                <p class="mt-1 text-sm text-slate-500">Track supplier balances, payment vouchers, and outstanding payables.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('supplier-ledger.index') }}" wire:navigate class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50 dark:border-zinc-800 dark:bg-zinc-900">Supplier Ledger</a>
                @can('supplier_payments.create')
                    <a href="{{ route('supplier-payments.create') }}" wire:navigate class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Record Payment</a>
                @endcan
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['Total Suppliers Balance', $this->stats['balance'], 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300'], ['Total Paid to Suppliers', $this->stats['paid'], 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300'], ['Outstanding Payables', $this->stats['outstanding'], 'bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300'], ['Payments This Month', $this->stats['month'], 'bg-violet-50 text-violet-700 dark:bg-violet-950/40 dark:text-violet-300']] as [$label, $value, $tone])
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                        <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $tone }}">EGP</span>
                    </div>
                    <p class="mt-3 text-2xl font-bold">{{ number_format($value, 2) }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 lg:grid-cols-6">
                <select wire:model.live="supplierId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All suppliers</option>
                    @foreach ($this->suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                    @endforeach
                </select>
                <input wire:model.live.debounce.300ms="invoiceNumber" placeholder="Invoice number" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <select wire:model.live="paymentMethodId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All methods</option>
                    @foreach ($this->paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->payment_method_name }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <button type="button" wire:click="resetFilters" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700">Reset</button>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div wire:loading.flex class="absolute inset-0 z-20 items-center justify-center bg-white/70 text-sm font-semibold dark:bg-zinc-950/70">Loading payments...</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Payment Number', 'Supplier', 'Invoice Number', 'Payment Method', 'Amount', 'Payment Date', 'Created By', 'Actions'] as $heading)
                                <th class="px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->payments as $payment)
                            <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-3 font-mono font-semibold">{{ $payment->payment_number }}</td>
                                <td class="px-4 py-3">{{ $payment->supplier?->supplier_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $payment->purchaseInvoice?->invoice_number ?? 'Unallocated' }}</td>
                                <td class="px-4 py-3">{{ $payment->paymentMethod?->payment_method_name ?? '-' }}</td>
                                <td class="px-4 py-3 font-semibold text-emerald-700 dark:text-emerald-300">{{ number_format((float) $payment->amount, 2) }}</td>
                                <td class="px-4 py-3">{{ $payment->payment_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ $payment->createdBy?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('supplier-payments.show', $payment) }}" wire:navigate class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">View</a>
                                        @can('supplier_payments.print')
                                            <a href="{{ route('supplier-payments.print', $payment) }}" target="_blank" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">Print Voucher</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="px-4 py-14 text-center text-slate-500">No supplier payments found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 p-4 dark:border-zinc-800">{{ $this->payments->links() }}</div>
        </div>
    </div>
</div>
