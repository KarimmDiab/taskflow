<?php

use App\Models\Branches;
use App\Models\Customer;
use App\Models\PaymentMethod;
use App\Models\SalesInvoice;
use App\Models\SalesReturn;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Sales Invoices')] class extends Component {
    use WithPagination;

    public string $invoiceNumber = '';
    public ?int $customerId = null;
    public ?int $branchId = null;
    public ?int $paymentMethodId = null;
    public string $paymentStatus = '';
    public string $invoiceStatus = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function updated($property): void
    {
        if (in_array($property, ['invoiceNumber', 'customerId', 'branchId', 'paymentMethodId', 'paymentStatus', 'invoiceStatus', 'dateFrom', 'dateTo'], true)) {
            $this->resetPage();
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['invoiceNumber', 'customerId', 'branchId', 'paymentMethodId', 'paymentStatus', 'invoiceStatus', 'dateFrom', 'dateTo']);
        $this->resetPage();
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

    public function getStatsProperty(): array
    {
        $base = $this->filteredQuery();
        $returns = SalesReturn::query()->whereIn('status', ['approved', 'completed']);

        return [
            'total_invoices' => (clone $base)->count(),
            'total_sales' => (clone $base)->where('status', '!=', 'cancelled')->sum('total_amount'),
            'net_sales' => (clone $base)->where('status', '!=', 'cancelled')->sum('net_total') - (clone $returns)->sum('return_amount'),
            'total_returns' => (clone $returns)->sum('return_amount'),
            'total_paid' => (clone $base)->where('status', '!=', 'cancelled')->sum('paid_amount'),
            'outstanding' => (clone $base)->where('status', '!=', 'cancelled')->sum('remaining_amount'),
        ];
    }

    public function getInvoicesProperty()
    {
        return $this->filteredQuery()
            ->with(['customer', 'branch', 'paymentMethod', 'user'])
            ->latest()
            ->paginate(12);
    }

    private function filteredQuery()
    {
        return SalesInvoice::query()
            ->when($this->invoiceNumber, fn ($query) => $query->where('invoice_number', 'like', "%{$this->invoiceNumber}%"))
            ->when($this->customerId, fn ($query) => $query->where('customer_id', $this->customerId))
            ->when($this->branchId, fn ($query) => $query->where('branch_id', $this->branchId))
            ->when($this->paymentMethodId, fn ($query) => $query->where('payment_method_id', $this->paymentMethodId))
            ->when($this->invoiceStatus, fn ($query) => $query->where('status', $this->invoiceStatus))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('created_at', '<=', $this->dateTo))
            ->when($this->paymentStatus === 'paid', fn ($query) => $query->where('remaining_amount', '<=', 0))
            ->when($this->paymentStatus === 'partial', fn ($query) => $query->where('paid_amount', '>', 0)->where('remaining_amount', '>', 0))
            ->when($this->paymentStatus === 'unpaid', fn ($query) => $query->where('paid_amount', '<=', 0));
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Sales Management</p>
                <h1 class="text-2xl font-bold tracking-tight">Sales Invoices</h1>
            </div>
            <a href="{{ route('pos_system') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700">
                New POS Sale
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-6">
            @foreach ([
                ['Total Invoices', $this->stats['total_invoices']],
                ['Total Sales', number_format($this->stats['total_sales'], 2)],
                ['Net Sales', number_format($this->stats['net_sales'], 2)],
                ['Total Returns', number_format($this->stats['total_returns'], 2)],
                ['Total Paid', number_format($this->stats['total_paid'], 2)],
                ['Outstanding', number_format($this->stats['outstanding'], 2)],
            ] as [$label, $value])
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 dark:text-zinc-400">{{ $label }}</p>
                    <p class="mt-2 text-xl font-bold">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-4 xl:grid-cols-7">
                <input wire:model.live.debounce.350ms="invoiceNumber" placeholder="Invoice number" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <select wire:model.live="customerId" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All customers</option>
                    @foreach ($this->customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="branchId" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All branches</option>
                    @foreach ($this->branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                <select wire:model.live="paymentMethodId" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">Payment method</option>
                    @foreach ($this->paymentMethods as $method)
                        <option value="{{ $method->id }}">{{ $method->payment_method_name }}</option>
                    @endforeach
                </select>
                <button type="button" wire:click="resetFilters" class="rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700 dark:hover:bg-zinc-800">Reset</button>
                <select wire:model.live="paymentStatus" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">Payment status</option>
                    <option value="paid">Paid</option>
                    <option value="partial">Partial</option>
                    <option value="unpaid">Unpaid</option>
                </select>
                <select wire:model.live="invoiceStatus" class="rounded-lg border-slate-200 text-sm shadow-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">Invoice status</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div wire:loading class="w-full border-b border-blue-100 bg-blue-50 px-4 py-2 text-sm text-blue-700 dark:border-blue-900 dark:bg-blue-950/40 dark:text-blue-300">Loading invoices...</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Invoice No', 'Customer', 'Branch', 'Date', 'Total', 'Paid', 'Remaining', 'Payment Method', 'Status', 'Created By', 'Actions'] as $heading)
                                <th class="whitespace-nowrap px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->invoices as $invoice)
                            <tr class="hover:bg-slate-50/70 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-3 font-semibold">{{ $invoice->invoice_number }}</td>
                                <td class="px-4 py-3">{{ $invoice->customer?->customer_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $invoice->branch?->branch_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $invoice->created_at?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $invoice->net_total, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $invoice->paid_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $invoice->remaining_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ $invoice->paymentMethod?->payment_method_name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $invoice->status === 'cancelled' ? 'bg-red-100 text-red-700 dark:bg-red-950 dark:text-red-300' : 'bg-emerald-100 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' }}">{{ ucfirst($invoice->status) }}</span>
                                    <span class="ml-1 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 dark:bg-zinc-800 dark:text-zinc-300">{{ ucfirst($invoice->payment_status) }}</span>
                                </td>
                                <td class="px-4 py-3">{{ $invoice->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        <a href="{{ route('sales.show', $invoice) }}" wire:navigate class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">View</a>
                                        @can('sales.print')
                                            <a href="{{ route('sales.print.a4', $invoice) }}" target="_blank" class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">Print</a>
                                        @endcan
                                        @if ($invoice->status !== 'cancelled')
                                            @can('sales.edit')
                                                <a href="{{ route('sales.edit', $invoice) }}" wire:navigate class="rounded-md border border-slate-200 px-2 py-1 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">Edit</a>
                                            @endcan
                                            @can('sales.return.create')
                                                <a href="{{ route('sales.show', ['invoice' => $invoice->id, 'return' => 1]) }}" wire:navigate class="rounded-md bg-blue-600 px-2 py-1 text-xs font-semibold text-white hover:bg-blue-700">Return</a>
                                            @endcan
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-12 text-center text-slate-500 dark:text-zinc-400">No sales invoices match the current filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-100 px-4 py-3 dark:border-zinc-800">{{ $this->invoices->links() }}</div>
        </div>
    </div>
</div>
