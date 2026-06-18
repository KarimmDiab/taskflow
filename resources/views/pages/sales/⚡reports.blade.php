<?php

use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Sales Reports')] class extends Component {
    public string $dateFrom = '';
    public string $dateTo = '';

    public function getCardsProperty(): array
    {
        $sales = $this->invoiceQuery()->where('status', '!=', 'cancelled');
        $returns = $this->returnQuery()->whereIn('status', ['approved', 'completed']);
        $totalSales = (float) (clone $sales)->sum('net_total');
        $totalReturns = (float) (clone $returns)->sum('return_amount');
        $grossProfit = (float) SalesInvoiceDetail::query()
            ->whereHas('salesInvoice', fn ($query) => $this->applyInvoiceFilters($query)->where('status', '!=', 'cancelled'))
            ->sum(DB::raw('line_total - (cost_price * product_quantity)'));
        $netSales = $totalSales - $totalReturns;

        return [
            'total_sales' => $totalSales,
            'total_returns' => $totalReturns,
            'net_sales' => $netSales,
            'gross_profit' => $grossProfit,
            'profit_margin' => $netSales > 0 ? ($grossProfit / $netSales) * 100 : 0,
        ];
    }

    public function getByBranchProperty()
    {
        return $this->invoiceQuery()
            ->selectRaw('branch_id, sum(net_total) as total')
            ->with('branch')
            ->where('status', '!=', 'cancelled')
            ->groupBy('branch_id')
            ->orderByDesc('total')
            ->get();
    }

    public function getByPeriodProperty()
    {
        return $this->invoiceQuery()
            ->selectRaw('date(created_at) as period, sum(net_total) as total, count(*) as invoices')
            ->where('status', '!=', 'cancelled')
            ->groupBy('period')
            ->orderByDesc('period')
            ->limit(14)
            ->get();
    }

    public function getByUserProperty()
    {
        return $this->invoiceQuery()
            ->selectRaw('user_id, sum(net_total) as total')
            ->with('user')
            ->where('status', '!=', 'cancelled')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->get();
    }

    public function getTopProductsProperty()
    {
        return SalesInvoiceDetail::query()
            ->selectRaw('product_variant_id, sum(product_quantity) as qty, sum(line_total) as total')
            ->with('productVariant.product')
            ->whereHas('salesInvoice', fn ($query) => $this->applyInvoiceFilters($query)->where('status', '!=', 'cancelled'))
            ->groupBy('product_variant_id')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();
    }

    public function getTopCustomersProperty()
    {
        return $this->invoiceQuery()
            ->selectRaw('customer_id, sum(net_total) as total')
            ->with('customer')
            ->where('status', '!=', 'cancelled')
            ->groupBy('customer_id')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    public function getTopReturnedProductsProperty()
    {
        return SalesReturnItem::query()
            ->selectRaw('product_variant_id, sum(quantity) as qty, sum(line_total) as total')
            ->with('productVariant.product')
            ->whereHas('salesReturn', fn ($query) => $this->applyReturnFilters($query)->whereIn('status', ['approved', 'completed']))
            ->groupBy('product_variant_id')
            ->orderByDesc('qty')
            ->limit(10)
            ->get();
    }

    public function getReturnReasonsProperty()
    {
        return $this->returnQuery()
            ->selectRaw('reason, count(*) as total')
            ->groupBy('reason')
            ->orderByDesc('total')
            ->limit(10)
            ->get();
    }

    private function invoiceQuery()
    {
        return $this->applyInvoiceFilters(SalesInvoice::query());
    }

    private function returnQuery()
    {
        return $this->applyReturnFilters(SalesReturn::query());
    }

    private function applyInvoiceFilters($query)
    {
        return $query
            ->when($this->dateFrom, fn ($inner) => $inner->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($inner) => $inner->whereDate('created_at', '<=', $this->dateTo));
    }

    private function applyReturnFilters($query)
    {
        return $query
            ->when($this->dateFrom, fn ($inner) => $inner->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($inner) => $inner->whereDate('created_at', '<=', $this->dateTo));
    }
}; ?>

<div class="min-h-screen bg-slate-50 px-4 py-6 text-slate-900 dark:bg-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Reports & Analytics</p>
                <h1 class="text-2xl font-bold tracking-tight">Sales Reports</h1>
            </div>
            <div class="flex gap-2">
                <input type="date" wire:model.live="dateFrom" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
                <input type="date" wire:model.live="dateTo" class="rounded-lg border-slate-200 text-sm dark:border-zinc-700 dark:bg-zinc-950">
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
            @foreach ([
                ['Total Sales', number_format($this->cards['total_sales'], 2)],
                ['Total Returns', number_format($this->cards['total_returns'], 2)],
                ['Net Sales', number_format($this->cards['net_sales'], 2)],
                ['Gross Profit', number_format($this->cards['gross_profit'], 2)],
                ['Profit Margin %', number_format($this->cards['profit_margin'], 2).'%'],
            ] as [$label, $value])
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $label }}</p>
                    <p class="mt-2 text-xl font-bold">{{ $value }}</p>
                </div>
            @endforeach
        </div>

        <div class="grid gap-4 lg:grid-cols-2">
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Sales by Period</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($this->byPeriod as $row)
                        <div class="flex justify-between rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-800/60">
                            <span>{{ $row->period }} · {{ $row->invoices }} invoices</span>
                            <span class="font-semibold">{{ number_format((float) $row->total, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No sales for this period.</p>
                    @endforelse
                </div>
            </div>

            @foreach ([
                'Sales by Branch' => [$this->byBranch, 'branch.branch_name'],
                'Sales by User' => [$this->byUser, 'user.name'],
                'Top Customers' => [$this->topCustomers, 'customer.customer_name'],
            ] as $title => [$rows, $path])
                <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                    <h2 class="font-bold">{{ $title }}</h2>
                    <div class="mt-4 space-y-2">
                        @forelse ($rows as $row)
                            <div class="flex justify-between rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-800/60">
                                <span>{{ data_get($row, $path) ?? 'Unknown' }}</span>
                                <span class="font-semibold">{{ number_format((float) $row->total, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No data for this period.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Top Selling Products</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($this->topProducts as $row)
                        <div class="flex justify-between rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-800/60">
                            <span>{{ $row->productVariant?->product?->product_name ?? 'Unknown' }}</span>
                            <span class="font-semibold">{{ (int) $row->qty }} pcs · {{ number_format((float) $row->total, 2) }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No product sales yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Top Returned Products</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($this->topReturnedProducts as $row)
                        <div class="flex justify-between rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-800/60">
                            <span>{{ $row->productVariant?->product?->product_name ?? 'Unknown' }}</span>
                            <span class="font-semibold">{{ (int) $row->qty }} pcs</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No returned products yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm dark:border-zinc-800 dark:bg-zinc-900">
                <h2 class="font-bold">Return Reasons Analysis</h2>
                <div class="mt-4 space-y-2">
                    @forelse ($this->returnReasons as $row)
                        <div class="flex justify-between rounded-lg bg-slate-50 p-3 text-sm dark:bg-zinc-800/60">
                            <span>{{ $row->reason }}</span>
                            <span class="font-semibold">{{ $row->total }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500">No return reasons yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
