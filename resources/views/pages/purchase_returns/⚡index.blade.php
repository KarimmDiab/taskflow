<?php

use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Models\SupplierCredit;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Purchase Returns')] class extends Component {
    use WithPagination;

    public string $supplierId = '';
    public string $invoiceNumber = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public int $perPage = 15;

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('purchase_returns.view'), 403);
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->orderBy('supplier_name')->get(['id', 'supplier_name']);
    }

    #[Computed]
    public function stats(): array
    {
        return [
            'count' => PurchaseReturn::query()->count(),
            'amount' => (float) PurchaseReturn::query()->sum('total_amount'),
            'month' => (float) PurchaseReturn::query()->whereBetween('return_date', [now()->startOfMonth()->toDateString(), now()->endOfMonth()->toDateString()])->sum('total_amount'),
            'credits' => (float) SupplierCredit::query()->sum('remaining_amount'),
        ];
    }

    #[Computed]
    public function returns()
    {
        return PurchaseReturn::query()
            ->with(['supplier', 'purchaseInvoice', 'createdBy'])
            ->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId))
            ->when($this->invoiceNumber !== '', fn ($query) => $query->whereHas('purchaseInvoice', fn ($invoice) => $invoice->where('invoice_number', 'like', '%' . $this->invoiceNumber . '%')))
            ->when($this->dateFrom !== '', fn ($query) => $query->whereDate('return_date', '>=', $this->dateFrom))
            ->when($this->dateTo !== '', fn ($query) => $query->whereDate('return_date', '<=', $this->dateTo))
            ->latest('return_date')
            ->latest()
            ->paginate($this->perPage);
    }

    public function resetFilters(): void
    {
        $this->reset(['supplierId', 'invoiceNumber', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function updating($property): void
    {
        if (in_array($property, ['supplierId', 'invoiceNumber', 'dateFrom', 'dateTo', 'perPage'], true)) {
            $this->resetPage();
        }
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">Supplier operations</p>
                <h1 class="text-2xl font-bold tracking-tight">Purchase Returns</h1>
                <p class="mt-1 text-sm text-slate-500">Return purchased stock and adjust supplier payables in one controlled document.</p>
            </div>
            @can('purchase_returns.create')
                <a href="{{ route('purchase-returns.create') }}" wire:navigate class="rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700">Create Purchase Return</a>
            @endcan
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([['Total Purchase Returns', $this->stats['count'], 'Returns'], ['Total Return Amount', $this->stats['amount'], 'EGP'], ['Returns This Month', $this->stats['month'], 'EGP'], ['Supplier Credits', $this->stats['credits'], 'EGP']] as [$label, $value, $suffix])
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                    <p class="mt-3 text-2xl font-bold">{{ is_numeric($value) && $suffix === 'EGP' ? number_format((float) $value, 2) : number_format((float) $value) }}</p>
                    <p class="mt-1 text-xs font-semibold text-emerald-600">{{ $suffix }}</p>
                </div>
            @endforeach
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 lg:grid-cols-5">
                <select wire:model.live="supplierId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All suppliers</option>
                    @foreach ($this->suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                    @endforeach
                </select>
                <input wire:model.live.debounce.300ms="invoiceNumber" placeholder="Invoice number" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <button type="button" wire:click="resetFilters" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700">Reset</button>
            </div>
        </div>

        <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div wire:loading.flex class="absolute inset-0 z-20 items-center justify-center bg-white/70 text-sm font-semibold dark:bg-zinc-950/70">Loading returns...</div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Return Number', 'Supplier', 'Purchase Invoice', 'Return Date', 'Return Amount', 'Created By', 'Actions'] as $heading)
                                <th class="px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->returns as $return)
                            <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-3 font-mono font-semibold">{{ $return->return_number }}</td>
                                <td class="px-4 py-3">{{ $return->supplier?->supplier_name ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $return->purchaseInvoice?->invoice_number ?? '-' }}</td>
                                <td class="px-4 py-3">{{ $return->return_date?->format('Y-m-d') }}</td>
                                <td class="px-4 py-3 font-semibold text-emerald-700 dark:text-emerald-300">{{ number_format((float) $return->total_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ $return->createdBy?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('purchase-returns.show', $return) }}" wire:navigate class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">View</a>
                                        @can('purchase_returns.print')
                                            <a href="{{ route('purchase-returns.print', $return) }}" target="_blank" class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold hover:bg-slate-50 dark:border-zinc-700">Print</a>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-14 text-center text-slate-500">No purchase returns found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-slate-200 p-4 dark:border-zinc-800">{{ $this->returns->links() }}</div>
        </div>
    </div>
</div>
