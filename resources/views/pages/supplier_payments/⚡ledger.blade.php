<?php

use App\Models\PurchaseInvoice;
use App\Models\PurchaseReturn;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Supplier Ledger')] class extends Component {
    public string $supplierId = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('supplier_payments.view'), 403);
        $this->supplierId = (string) request()->integer('supplier_id', 0);
        $this->dateFrom = request('date_from', '');
        $this->dateTo = request('date_to', '');
    }

    #[Computed]
    public function suppliers()
    {
        return Supplier::query()->orderBy('supplier_name')->get(['id', 'supplier_name', 'supplier_phone', 'supplier_address']);
    }

    #[Computed]
    public function supplier(): ?Supplier
    {
        return $this->supplierId !== '' && (int) $this->supplierId > 0
            ? Supplier::query()->find($this->supplierId)
            : null;
    }

    #[Computed]
    public function totals(): array
    {
        $purchaseQuery = PurchaseInvoice::query()->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId));
        $paymentQuery = SupplierPayment::query()->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId));
        $returnQuery = PurchaseReturn::query()->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId));

        $this->applyDates($purchaseQuery, 'purchase_invoice_date');
        $this->applyDates($paymentQuery, 'payment_date');
        $this->applyDates($returnQuery, 'return_date');

        $purchases = (float) $purchaseQuery->sum('total_amount');
        $payments = (float) $paymentQuery->sum('amount');
        $returns = (float) $returnQuery->sum('total_amount');

        return [
            'purchases' => $purchases,
            'payments' => $payments,
            'returns' => $returns,
            'balance' => $purchases - $payments - $returns,
        ];
    }

    #[Computed]
    public function transactions()
    {
        $invoiceQuery = PurchaseInvoice::query()
            ->with('supplier')
            ->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId));
        $this->applyDates($invoiceQuery, 'purchase_invoice_date');

        $paymentQuery = SupplierPayment::query()
            ->with(['supplier', 'purchaseInvoice'])
            ->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId));
        $this->applyDates($paymentQuery, 'payment_date');

        $returnQuery = PurchaseReturn::query()
            ->with(['supplier', 'purchaseInvoice'])
            ->when($this->supplierId !== '', fn ($query) => $query->where('supplier_id', $this->supplierId));
        $this->applyDates($returnQuery, 'return_date');

        $rows = collect()
            ->merge($invoiceQuery->get()->map(fn ($invoice) => [
                'date' => $invoice->purchase_invoice_date,
                'type' => 'Invoice',
                'reference' => $invoice->invoice_number,
                'supplier' => $invoice->supplier?->supplier_name,
                'debit' => (float) $invoice->total_amount,
                'credit' => 0.0,
            ]))
            ->merge($paymentQuery->get()->map(fn ($payment) => [
                'date' => $payment->payment_date,
                'type' => 'Payment',
                'reference' => $payment->payment_number . ($payment->purchaseInvoice ? ' / ' . $payment->purchaseInvoice->invoice_number : ''),
                'supplier' => $payment->supplier?->supplier_name,
                'debit' => 0.0,
                'credit' => (float) $payment->amount,
            ]))
            ->merge($returnQuery->get()->map(fn ($return) => [
                'date' => $return->return_date,
                'type' => 'Return',
                'reference' => $return->return_number . ' / ' . ($return->purchaseInvoice?->invoice_number ?? '-'),
                'supplier' => $return->supplier?->supplier_name,
                'debit' => 0.0,
                'credit' => (float) $return->total_amount,
            ]))
            ->sortBy([['date', 'asc'], ['type', 'asc']])
            ->values();

        $balance = 0.0;

        return $rows->map(function (array $row) use (&$balance): array {
            $balance += $row['debit'] - $row['credit'];
            $row['balance'] = $balance;

            return $row;
        });
    }

    public function exportExcel()
    {
        abort_unless(auth()->user()?->can('supplier_payments.export'), 403);

        $filename = 'supplier-ledger-' . now()->format('Y-m-d-His') . '.csv';
        $rows = $this->transactions;

        return response()->streamDownload(function () use ($rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Date', 'Supplier', 'Type', 'Reference Number', 'Debit', 'Credit', 'Running Balance']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    optional($row['date'])->format('Y-m-d'),
                    $row['supplier'],
                    $row['type'],
                    $row['reference'],
                    number_format($row['debit'], 2, '.', ''),
                    number_format($row['credit'], 2, '.', ''),
                    number_format($row['balance'], 2, '.', ''),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function applyDates($query, string $column): void
    {
        if ($this->dateFrom !== '') {
            $query->whereDate($column, '>=', $this->dateFrom);
        }

        if ($this->dateTo !== '') {
            $query->whereDate($column, '<=', $this->dateTo);
        }
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-emerald-600">Supplier accounts</p>
                <h1 class="text-2xl font-bold tracking-tight">Supplier Ledger</h1>
                <p class="mt-1 text-sm text-slate-500">Invoice debits, payment credits, and running balances.</p>
            </div>
            <div class="flex flex-wrap gap-2">
                @can('supplier_payments.export')
                    <button type="button" wire:click="exportExcel" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50 dark:border-zinc-800 dark:bg-zinc-900">Excel</button>
                @endcan
                @can('supplier_payments.print')
                    <a href="{{ route('supplier-ledger.print', ['supplier_id' => $supplierId, 'date_from' => $dateFrom, 'date_to' => $dateTo]) }}" target="_blank" class="rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-semibold shadow-sm hover:bg-slate-50 dark:border-zinc-800 dark:bg-zinc-900">PDF / Print</a>
                @endcan
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="grid gap-3 md:grid-cols-4">
                <select wire:model.live="supplierId" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                    <option value="">All suppliers</option>
                    @foreach ($this->suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->supplier_name }}</option>
                    @endforeach
                </select>
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <a href="{{ route('supplier-ledger.index') }}" wire:navigate class="rounded-lg border border-slate-200 px-4 py-2 text-center text-sm font-semibold hover:bg-slate-50 dark:border-zinc-700">Reset</a>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <p class="text-sm font-medium text-slate-500">Supplier</p>
                <p class="mt-2 font-bold">{{ $this->supplier?->supplier_name ?? 'All suppliers' }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ $this->supplier?->supplier_phone }}</p>
            </div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Total Purchases</p><p class="mt-2 text-2xl font-bold">{{ number_format($this->totals['purchases'], 2) }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Payments + Returns</p><p class="mt-2 text-2xl font-bold text-emerald-600">{{ number_format($this->totals['payments'] + $this->totals['returns'], 2) }}</p><p class="mt-1 text-xs text-slate-500">Returns: {{ number_format($this->totals['returns'], 2) }}</p></div>
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900"><p class="text-sm text-slate-500">Outstanding Balance</p><p class="mt-2 text-2xl font-bold text-amber-600">{{ number_format($this->totals['balance'], 2) }}</p></div>
        </div>

        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm dark:divide-zinc-800">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-500 dark:bg-zinc-900 dark:text-zinc-400">
                        <tr>
                            @foreach (['Date', 'Type', 'Reference Number', 'Supplier', 'Debit', 'Credit', 'Running Balance'] as $heading)
                                <th class="px-4 py-3 text-left font-semibold">{{ $heading }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-zinc-800">
                        @forelse ($this->transactions as $row)
                            <tr class="hover:bg-slate-50 dark:hover:bg-zinc-800/60">
                                <td class="px-4 py-3">{{ optional($row['date'])->format('Y-m-d') }}</td>
                                <td class="px-4 py-3"><span class="rounded-full px-2.5 py-1 text-xs font-semibold {{ $row['type'] === 'Invoice' ? 'bg-blue-50 text-blue-700 dark:bg-blue-950/40 dark:text-blue-300' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300' }}">{{ $row['type'] }}</span></td>
                                <td class="px-4 py-3 font-mono">{{ $row['reference'] }}</td>
                                <td class="px-4 py-3">{{ $row['supplier'] }}</td>
                                <td class="px-4 py-3">{{ $row['debit'] > 0 ? number_format($row['debit'], 2) : '-' }}</td>
                                <td class="px-4 py-3">{{ $row['credit'] > 0 ? number_format($row['credit'], 2) : '-' }}</td>
                                <td class="px-4 py-3 font-semibold">{{ number_format($row['balance'], 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-4 py-14 text-center text-slate-500">No ledger transactions found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
