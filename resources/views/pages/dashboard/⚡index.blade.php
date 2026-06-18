<?php

use App\Models\Branches;
use App\Models\Customer;
use App\Models\ExpensesDetail;
use App\Models\Inventory;
use App\Models\InventoryAdjustment;
use App\Models\OnlineOrder;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseReturn;
use App\Models\SalesInvoice;
use App\Models\SalesInvoiceDetail;
use App\Models\SalesReturn;
use App\Models\SalesReturnItem;
use App\Models\StockMovement;
use App\Models\StockTransfer;
use App\Models\Supplier;
use App\Models\SupplierPayment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('ERP Dashboard')] class extends Component {
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $channel = 'all';
    public string $branchId = '';

    public function mount(): void
    {
        abort_unless(auth()->user()?->can('dashboard.view'), 403);

        $this->dateFrom = now()->startOfMonth()->toDateString();
        $this->dateTo = now()->toDateString();
    }

    public function updated($property): void
    {
        if (in_array($property, ['dateFrom', 'dateTo', 'channel', 'branchId'], true)) {
            $this->dispatch('dashboardChartsUpdated', chartData: $this->chartPayload);
        }
    }

    public function getBranchesProperty()
    {
        return Branches::query()->orderBy('branch_name')->get(['id', 'branch_name']);
    }

    public function getKpisProperty(): array
    {
        $sales = $this->salesInvoiceQuery();
        $salesIdsQuery = $this->salesInvoiceQuery()->select('sales_invoices.id');

        $grossSales = (float) (clone $sales)->sum(DB::raw('COALESCE(NULLIF(subtotal, 0), total_amount)'));
        $discounts = (float) (clone $sales)->sum(DB::raw('COALESCE(NULLIF(discount_amount, 0), deduction, 0)'));
        $salesReturns = (float) $this->salesReturnQuery()->sum('return_amount');
        $netSales = max($grossSales - $discounts - $salesReturns, 0);
        $cost = (float) SalesInvoiceDetail::query()
            ->whereIn('sales_invoice_id', $salesIdsQuery)
            ->sum(DB::raw('cost_price * product_quantity'));
        $grossProfit = $netSales - $cost;
        $totalPurchases = (float) $this->purchaseInvoiceQuery()->sum('total_amount');
        $purchaseReturns = (float) $this->purchaseReturnQuery()->sum('total_amount');
        $expenses = (float) $this->expensesQuery()->sum('expenses_cost');

        return [
            'total_sales' => $grossSales,
            'net_sales' => $netSales,
            'total_orders' => (clone $sales)->count(),
            'online_orders' => $this->onlineOrdersQuery()->count(),
            'pos_sales' => $this->salesInvoiceQuery()->whereDoesntHave('onlineOrder')->count(),
            'total_purchases' => $totalPurchases,
            'total_returns' => $salesReturns + $purchaseReturns,
            'gross_profit' => $grossProfit,
            'total_expenses' => $expenses,
            'low_stock_products' => $this->inventoryQuery()->where('inventories.quantity', '>', 0)->where('inventories.quantity', '<=', 5)->count(),
            'out_of_stock_products' => $this->inventoryQuery()->where('inventories.quantity', '<=', 0)->count(),
            'total_customers' => Customer::query()->count(),
            'total_suppliers' => Supplier::query()->count(),
        ];
    }

    public function getFinanceProperty(): array
    {
        $sales = $this->salesInvoiceQuery();
        $grossSales = (float) (clone $sales)->sum(DB::raw('COALESCE(NULLIF(subtotal, 0), total_amount)'));
        $discounts = (float) (clone $sales)->sum(DB::raw('COALESCE(NULLIF(discount_amount, 0), deduction, 0)'));
        $returns = (float) $this->salesReturnQuery()->sum('return_amount');
        $netSales = max($grossSales - $discounts - $returns, 0);
        $cost = (float) SalesInvoiceDetail::query()
            ->whereIn('sales_invoice_id', $this->salesInvoiceQuery()->select('sales_invoices.id'))
            ->sum(DB::raw('cost_price * product_quantity'));
        $expenses = (float) $this->expensesQuery()->sum('expenses_cost');
        $grossProfit = $netSales - $cost;

        return [
            'gross_sales' => $grossSales,
            'discounts' => $discounts,
            'returns' => $returns,
            'net_sales' => $netSales,
            'cost' => $cost,
            'gross_profit' => $grossProfit,
            'expenses' => $expenses,
            'estimated_net_profit' => $grossProfit - $expenses,
            'profit_margin' => $netSales > 0 ? ($grossProfit / $netSales) * 100 : 0,
        ];
    }

    public function getInventoryOverviewProperty(): array
    {
        $inventoryValue = (float) $this->inventoryQuery()
            ->join('product_variants', 'inventories.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->sum(DB::raw('inventories.quantity * COALESCE(NULLIF(product_variants.variant_cost, 0), products.product_cost, 0)'));

        return [
            'value' => $inventoryValue,
            'low_stock' => $this->inventoryQuery()
                ->with(['productVariant.product', 'productVariant.color', 'productVariant.size', 'branch'])
                ->where('quantity', '>', 0)
                ->where('quantity', '<=', 5)
                ->orderBy('quantity')
                ->limit(6)
                ->get(),
            'out_of_stock' => $this->inventoryQuery()->where('quantity', '<=', 0)->count(),
            'movements' => $this->stockMovementQuery()
                ->with(['branch', 'productVariant.product'])
                ->latest('movement_date')
                ->limit(7)
                ->get(),
            'adjustments' => $this->inventoryAdjustmentQuery()
                ->selectRaw('adjustment_type, count(*) as total, sum(abs(adjustment_quantity)) as qty')
                ->groupBy('adjustment_type')
                ->orderByDesc('total')
                ->limit(5)
                ->get(),
            'transfers' => $this->stockTransferQuery()
                ->selectRaw('status, count(*) as total')
                ->groupBy('status')
                ->orderByDesc('total')
                ->get(),
        ];
    }

    public function getPurchasesOverviewProperty(): array
    {
        return [
            'count' => $this->purchaseInvoiceQuery()->count(),
            'amount' => (float) $this->purchaseInvoiceQuery()->sum('total_amount'),
            'payments' => (float) $this->supplierPaymentQuery()->sum('amount'),
            'outstanding' => (float) $this->purchaseInvoiceQuery()->sum('remaining_amount'),
            'returns' => (float) $this->purchaseReturnQuery()->sum('total_amount'),
        ];
    }

    public function getReturnsOverviewProperty(): array
    {
        $salesCount = max($this->salesInvoiceQuery()->count(), 1);
        $returnCount = $this->salesReturnQuery()->count();

        return [
            'sales_returns' => (float) $this->salesReturnQuery()->sum('return_amount'),
            'purchase_returns' => (float) $this->purchaseReturnQuery()->sum('total_amount'),
            'return_rate' => ($returnCount / $salesCount) * 100,
            'top_products' => SalesReturnItem::query()
                ->selectRaw('product_variant_id, sum(quantity) as qty, sum(line_total) as total')
                ->with('productVariant.product')
                ->whereHas('salesReturn', fn (Builder $query) => $this->applyDateFilter($query, 'created_at')->whereIn('status', ['approved', 'completed']))
                ->groupBy('product_variant_id')
                ->orderByDesc('qty')
                ->limit(5)
                ->get(),
        ];
    }

    public function getRecentActivityProperty(): array
    {
        return [
            'sales' => $this->salesInvoiceQuery()->with(['customer', 'branch'])->latest()->limit(5)->get(),
            'orders' => $this->onlineOrdersQuery()->with('shipping')->latest()->limit(5)->get(),
            'purchases' => $this->purchaseInvoiceQuery()->with('supplier')->latest('purchase_invoice_date')->limit(5)->get(),
            'movements' => $this->stockMovementQuery()->with(['productVariant.product', 'branch'])->latest('movement_date')->limit(5)->get(),
            'sales_returns' => $this->salesReturnQuery()->with('invoice.customer')->latest()->limit(5)->get(),
            'supplier_payments' => $this->supplierPaymentQuery()->with('supplier')->latest('payment_date')->limit(5)->get(),
        ];
    }

    public function getChartPayloadProperty(): array
    {
        return [
            'salesTrend' => $this->salesTrendData(),
            'salesByBranch' => $this->salesByBranchData(),
            'channels' => $this->channelData(),
            'payments' => $this->paymentData(),
            'topProducts' => $this->topProductsData(),
            'topCustomers' => $this->topCustomersData(),
        ];
    }

    private function salesTrendData(): array
    {
        $from = Carbon::parse($this->dateFrom ?: now()->startOfMonth());
        $to = Carbon::parse($this->dateTo ?: now());
        $isMonthly = $from->diffInDays($to) > 45;
        $expression = $isMonthly ? "DATE_FORMAT(created_at, '%Y-%m')" : 'DATE(created_at)';

        $rows = $this->salesInvoiceQuery()
            ->selectRaw("{$expression} as period, sum(net_total) as total")
            ->groupBy('period')
            ->orderBy('period')
            ->get();

        return [
            'labels' => $rows->pluck('period')->values(),
            'values' => $rows->pluck('total')->map(fn ($value) => round((float) $value, 2))->values(),
        ];
    }

    private function salesByBranchData(): array
    {
        $rows = $this->salesInvoiceQuery()
            ->join('branches', 'sales_invoices.branch_id', '=', 'branches.id')
            ->selectRaw('branches.branch_name as label, sum(sales_invoices.net_total) as total')
            ->groupBy('branches.id', 'branches.branch_name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return ['labels' => $rows->pluck('label')->values(), 'values' => $rows->pluck('total')->map(fn ($value) => round((float) $value, 2))->values()];
    }

    private function channelData(): array
    {
        $base = $this->salesInvoiceQuery();
        $online = (float) (clone $base)->whereHas('onlineOrder')->sum('net_total');
        $pos = (float) (clone $base)->whereDoesntHave('onlineOrder')->sum('net_total');

        return ['labels' => ['POS', 'Online'], 'values' => [round($pos, 2), round($online, 2)]];
    }

    private function paymentData(): array
    {
        $rows = $this->salesInvoiceQuery()
            ->leftJoin('payment_methods', 'sales_invoices.payment_method_id', '=', 'payment_methods.id')
            ->selectRaw("COALESCE(payment_methods.payment_method_name, 'Unknown') as label, sum(sales_invoices.net_total) as total")
            ->groupBy('payment_methods.id', 'payment_methods.payment_method_name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return ['labels' => $rows->pluck('label')->values(), 'values' => $rows->pluck('total')->map(fn ($value) => round((float) $value, 2))->values()];
    }

    private function topProductsData(): array
    {
        $rows = SalesInvoiceDetail::query()
            ->join('sales_invoices', 'sales_invoice_details.sales_invoice_id', '=', 'sales_invoices.id')
            ->join('product_variants', 'sales_invoice_details.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->when($this->branchId, fn ($query) => $query->where('sales_invoices.branch_id', $this->branchId))
            ->when($this->channel === 'online', fn ($query) => $query->whereExists(fn ($inner) => $inner
                ->selectRaw('1')->from('online_orders')->whereColumn('online_orders.sales_invoice_id', 'sales_invoices.id')))
            ->when($this->channel === 'pos', fn ($query) => $query->whereNotExists(fn ($inner) => $inner
                ->selectRaw('1')->from('online_orders')->whereColumn('online_orders.sales_invoice_id', 'sales_invoices.id')))
            ->where(fn ($query) => $query->where('sales_invoices.status', '!=', 'cancelled')->orWhereNull('sales_invoices.status'))
            ->when($this->dateFrom, fn ($query) => $query->whereDate('sales_invoices.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($query) => $query->whereDate('sales_invoices.created_at', '<=', $this->dateTo))
            ->selectRaw('products.product_name as label, sum(sales_invoice_details.product_quantity) as qty')
            ->groupBy('products.id', 'products.product_name')
            ->orderByDesc('qty')
            ->limit(8)
            ->get();

        return ['labels' => $rows->pluck('label')->values(), 'values' => $rows->pluck('qty')->map(fn ($value) => (int) $value)->values()];
    }

    private function topCustomersData(): array
    {
        $rows = $this->salesInvoiceQuery()
            ->leftJoin('customers', 'sales_invoices.customer_id', '=', 'customers.id')
            ->selectRaw("COALESCE(customers.customer_name, 'Walk-in') as label, sum(sales_invoices.net_total) as total")
            ->groupBy('customers.id', 'customers.customer_name')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        return ['labels' => $rows->pluck('label')->values(), 'values' => $rows->pluck('total')->map(fn ($value) => round((float) $value, 2))->values()];
    }

    private function salesInvoiceQuery(): Builder
    {
        return $this->applySalesChannel(
            $this->applyDateFilter(SalesInvoice::query(), 'sales_invoices.created_at')
                ->where(fn ($query) => $query->where('sales_invoices.status', '!=', 'cancelled')->orWhereNull('sales_invoices.status'))
                ->when($this->branchId, fn ($query) => $query->where('sales_invoices.branch_id', $this->branchId))
        );
    }

    private function onlineOrdersQuery(): Builder
    {
        return OnlineOrder::query()
            ->when($this->branchId || $this->dateFrom || $this->dateTo || $this->channel === 'pos', function ($query) {
                $query->whereHas('salesInvoice', fn ($invoice) => $this->salesInvoiceQueryConstraints($invoice));
            })
            ->when($this->channel === 'pos', fn ($query) => $query->whereRaw('1 = 0'));
    }

    private function salesInvoiceQueryConstraints(Builder $query): Builder
    {
        return $this->applyDateFilter($query, 'sales_invoices.created_at')
            ->where(fn ($inner) => $inner->where('sales_invoices.status', '!=', 'cancelled')->orWhereNull('sales_invoices.status'))
            ->when($this->branchId, fn ($inner) => $inner->where('sales_invoices.branch_id', $this->branchId));
    }

    private function purchaseInvoiceQuery(): Builder
    {
        return $this->applyDateFilter(PurchaseInvoice::query(), 'purchase_invoice_date')
            ->when($this->branchId, fn ($query) => $query->where('branch_id', $this->branchId));
    }

    private function purchaseReturnQuery(): Builder
    {
        return $this->applyDateFilter(PurchaseReturn::query(), 'return_date')
            ->when($this->branchId, fn ($query) => $query->whereHas('purchaseInvoice', fn ($invoice) => $invoice->where('branch_id', $this->branchId)));
    }

    private function salesReturnQuery(): Builder
    {
        return $this->applyDateFilter(SalesReturn::query(), 'created_at')
            ->whereIn('status', ['approved', 'completed'])
            ->whereHas('invoice', fn ($invoice) => $this->salesInvoiceQueryConstraints($invoice));
    }

    private function expensesQuery(): Builder
    {
        return $this->applyDateFilter(ExpensesDetail::query(), 'expenses_date');
    }

    private function inventoryQuery(): Builder
    {
        return Inventory::query()->when($this->branchId, fn ($query) => $query->where('branch_id', $this->branchId));
    }

    private function stockMovementQuery(): Builder
    {
        return $this->applyDateFilter(StockMovement::query(), 'movement_date')
            ->when($this->branchId, fn ($query) => $query->where('branch_id', $this->branchId));
    }

    private function inventoryAdjustmentQuery(): Builder
    {
        return $this->applyDateFilter(InventoryAdjustment::query(), 'adjustment_date')
            ->when($this->branchId, fn ($query) => $query->where('branch_id', $this->branchId));
    }

    private function stockTransferQuery(): Builder
    {
        return $this->applyDateFilter(StockTransfer::query(), 'transfer_date')
            ->when($this->branchId, fn ($query) => $query->where(fn ($inner) => $inner->where('from_branch_id', $this->branchId)->orWhere('to_branch_id', $this->branchId)));
    }

    private function supplierPaymentQuery(): Builder
    {
        return $this->applyDateFilter(SupplierPayment::query(), 'payment_date');
    }

    private function applyDateFilter(Builder $query, string $column): Builder
    {
        return $query
            ->when($this->dateFrom, fn ($inner) => $inner->whereDate($column, '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($inner) => $inner->whereDate($column, '<=', $this->dateTo));
    }

    private function applySalesChannel(Builder $query): Builder
    {
        return $query
            ->when($this->channel === 'online', fn ($inner) => $inner->whereHas('onlineOrder'))
            ->when($this->channel === 'pos', fn ($inner) => $inner->whereDoesntHave('onlineOrder'));
    }
};
?>

<!-- واجهة المستخدم المُحسَّنة بالكامل -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 px-4 py-8 text-slate-900 dark:from-zinc-950 dark:via-zinc-900 dark:to-zinc-950 dark:text-zinc-100 sm:px-6 lg:px-8" dir="ltr">
    <div class="mx-auto max-w-[1800px] space-y-8">

        <!-- Header Section -->
        <section class="relative overflow-hidden rounded-3xl border border-white/20 bg-white/70 p-6 shadow-xl shadow-slate-200/40 backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80 dark:shadow-black/20">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/5 via-purple-500/5 to-teal-500/5"></div>
            <div class="relative flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                <div>
                    <div class="mb-1 inline-flex items-center gap-2 rounded-full bg-indigo-100 px-3 py-1 text-xs font-semibold uppercase tracking-widest text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-300">
                        <span class="h-2 w-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        Fashion ERP Command Center
                    </div>
                    <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 dark:text-white">Main Dashboard</h1>
                    <p class="mt-2 max-w-2xl text-sm leading-relaxed text-slate-500 dark:text-slate-400">Live sales, inventory, purchasing, returns, and finance overview from your entire ERP ecosystem.</p>
                </div>
                <div class="grid w-full gap-3 sm:grid-cols-2 xl:w-auto xl:grid-cols-4">
                    <input type="date" wire:model.live="dateFrom" class="rounded-xl border-slate-200/80 bg-white/70 text-sm shadow-sm backdrop-blur-sm dark:border-zinc-700/80 dark:bg-zinc-950/70">
                    <input type="date" wire:model.live="dateTo" class="rounded-xl border-slate-200/80 bg-white/70 text-sm shadow-sm backdrop-blur-sm dark:border-zinc-700/80 dark:bg-zinc-950/70">
                    <select wire:model.live="branchId" class="rounded-xl border-slate-200/80 bg-white/70 text-sm shadow-sm backdrop-blur-sm dark:border-zinc-700/80 dark:bg-zinc-950/70">
                        <option value="">All branches</option>
                        @foreach ($this->branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                        @endforeach
                    </select>
                    <select wire:model.live="channel" class="rounded-xl border-slate-200/80 bg-white/70 text-sm shadow-sm backdrop-blur-sm dark:border-zinc-700/80 dark:bg-zinc-950/70">
                        <option value="all">All channels</option>
                        <option value="pos">POS only</option>
                        <option value="online">Online only</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- Loading State -->
        <div wire:loading.flex class="fixed inset-x-0 top-24 z-50 justify-center pointer-events-none">
            <div class="flex items-center gap-3 rounded-full border border-indigo-100 bg-white/90 px-5 py-2.5 text-sm font-semibold text-indigo-700 shadow-xl backdrop-blur-xl dark:border-indigo-800 dark:bg-zinc-900/90 dark:text-indigo-300">
                <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path></svg>
                Refreshing dashboard data...
            </div>
        </div>

        <!-- KPI Cards Grid -->
        <section class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-5">
            @foreach ([
                ['Total Sales', $this->kpis['total_sales'], 'EGP', 'bg-gradient-to-br from-blue-50 to-indigo-50 text-blue-700 dark:from-blue-950/30 dark:to-indigo-950/20 dark:text-blue-300', 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'],
                ['Net Sales', $this->kpis['net_sales'], 'EGP', 'bg-gradient-to-br from-emerald-50 to-teal-50 text-emerald-700 dark:from-emerald-950/30 dark:to-teal-950/20 dark:text-emerald-300', 'M5 10l7-7m0 0l7 7m-7-7v18'],
                ['Total Orders', $this->kpis['total_orders'], '', 'bg-gradient-to-br from-slate-100 to-gray-50 text-slate-700 dark:from-zinc-800 dark:to-zinc-900 dark:text-zinc-200', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                ['Online Orders', $this->kpis['online_orders'], '', 'bg-gradient-to-br from-rose-50 to-pink-50 text-rose-700 dark:from-rose-950/30 dark:to-pink-950/20 dark:text-rose-300', 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9'],
                ['POS Sales', $this->kpis['pos_sales'], '', 'bg-gradient-to-br from-indigo-50 to-blue-50 text-indigo-700 dark:from-indigo-950/30 dark:to-blue-950/20 dark:text-indigo-300', 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                ['Total Purchases', $this->kpis['total_purchases'], 'EGP', 'bg-gradient-to-br from-amber-50 to-yellow-50 text-amber-700 dark:from-amber-950/30 dark:to-yellow-950/20 dark:text-amber-300', 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4z'],
                ['Total Returns', $this->kpis['total_returns'], 'EGP', 'bg-gradient-to-br from-red-50 to-rose-50 text-red-700 dark:from-red-950/30 dark:to-rose-950/20 dark:text-red-300', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
                ['Gross Profit', $this->kpis['gross_profit'], 'EGP', 'bg-gradient-to-br from-teal-50 to-cyan-50 text-teal-700 dark:from-teal-950/30 dark:to-cyan-950/20 dark:text-teal-300', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Total Expenses', $this->kpis['total_expenses'], 'EGP', 'bg-gradient-to-br from-orange-50 to-amber-50 text-orange-700 dark:from-orange-950/30 dark:to-amber-950/20 dark:text-orange-300', 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z'],
                ['Low Stock', $this->kpis['low_stock_products'], '', 'bg-gradient-to-br from-yellow-50 to-amber-50 text-yellow-700 dark:from-yellow-950/30 dark:to-amber-950/20 dark:text-yellow-300', 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                ['Out of Stock', $this->kpis['out_of_stock_products'], '', 'bg-gradient-to-br from-zinc-100 to-slate-100 text-zinc-700 dark:from-zinc-800 dark:to-slate-900 dark:text-zinc-200', 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ['Customers', $this->kpis['total_customers'], '', 'bg-gradient-to-br from-cyan-50 to-blue-50 text-cyan-700 dark:from-cyan-950/30 dark:to-blue-950/20 dark:text-cyan-300', 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                ['Suppliers', $this->kpis['total_suppliers'], '', 'bg-gradient-to-br from-violet-50 to-purple-50 text-violet-700 dark:from-violet-950/30 dark:to-purple-950/20 dark:text-violet-300', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
            ] as [$label, $value, $suffix, $classes, $iconPath])
                <div class="group relative overflow-hidden rounded-2xl border border-white/20 bg-white/70 p-5 shadow-lg shadow-slate-200/50 backdrop-blur-md transition-all duration-300 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-200/70 dark:border-zinc-800/50 dark:bg-zinc-900/80 dark:shadow-black/20 dark:hover:shadow-black/40">
                    <div class="absolute -right-4 -top-4 h-24 w-24 opacity-10 group-hover:opacity-20 transition-opacity">
                        <svg fill="currentColor" viewBox="0 0 24 24"><path d="{{ $iconPath }}"/></svg>
                    </div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-slate-500 dark:text-slate-400">{{ $label }}</p>
                    <p class="mt-2 inline-block rounded-xl px-3 py-2 text-2xl font-black {{ $classes }}">
                        {{ is_numeric($value) ? number_format((float) $value, $suffix === '' ? 0 : 2) : $value }} {{ $suffix }}
                    </p>
                </div>
            @endforeach
        </section>

        <!-- Charts Section -->
        <section class="grid gap-5 xl:grid-cols-3">
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80 xl:col-span-2">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-indigo-500"></span> Sales Overview</h2>
                <div class="mt-4 h-80" wire:ignore><canvas id="salesTrendChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-rose-500"></span> POS vs Online</h2>
                <div class="mt-4 h-80" wire:ignore><canvas id="channelChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-blue-500"></span> Sales by Branch</h2>
                <div class="mt-4 h-72" wire:ignore><canvas id="branchChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-violet-500"></span> Payment Methods</h2>
                <div class="mt-4 h-72" wire:ignore><canvas id="paymentChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-teal-500"></span> Top Customers</h2>
                <div class="mt-4 h-72" wire:ignore><canvas id="customerChart"></canvas></div>
            </div>
        </section>

        <!-- Data Cards Row -->
        <section class="grid gap-5 xl:grid-cols-3">
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-emerald-500"></span> Top Selling Products</h2>
                <div class="mt-4 h-72" wire:ignore><canvas id="productChart"></canvas></div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-amber-500"></span> Inventory Overview</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>Total inventory value</dt><dd class="font-bold">{{ number_format($this->inventoryOverview['value'], 2) }} EGP</dd></div>
                    <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>Low stock variants</dt><dd class="font-bold">{{ number_format($this->kpis['low_stock_products']) }}</dd></div>
                    <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>Out of stock variants</dt><dd class="font-bold">{{ number_format($this->inventoryOverview['out_of_stock']) }}</dd></div>
                </dl>
                <div class="mt-5">
                    <h3 class="text-sm font-bold text-slate-500 dark:text-slate-400">Low Stock Variants</h3>
                    <div class="mt-3 space-y-2">
                        @forelse ($this->inventoryOverview['low_stock'] as $row)
                            <div class="flex items-center justify-between rounded-xl border border-slate-100/70 bg-white/40 p-3 text-sm backdrop-blur-sm dark:border-zinc-800 dark:bg-zinc-950/40">
                                <span>{{ $row->productVariant?->product?->product_name ?? 'Unknown' }} <span class="text-slate-400">at {{ $row->branch?->branch_name }}</span></span>
                                <span class="rounded-full bg-yellow-100 px-2.5 py-1 text-xs font-bold text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-300">{{ $row->quantity }}</span>
                            </div>
                        @empty
                            <p class="rounded-xl border border-dashed border-slate-200 p-4 text-center text-sm text-slate-500 dark:border-zinc-800">No low stock variants.</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-cyan-500"></span> Purchases Overview</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    @foreach ([
                        ['Purchase invoices', number_format($this->purchasesOverview['count'])],
                        ['Purchase amount', number_format($this->purchasesOverview['amount'], 2).' EGP'],
                        ['Supplier payments', number_format($this->purchasesOverview['payments'], 2).' EGP'],
                        ['Outstanding balance', number_format($this->purchasesOverview['outstanding'], 2).' EGP'],
                        ['Purchase returns', number_format($this->purchasesOverview['returns'], 2).' EGP'],
                    ] as [$label, $value])
                        <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>{{ $label }}</dt><dd class="font-bold">{{ $value }}</dd></div>
                    @endforeach
                </dl>
            </div>
        </section>

        <!-- Returns & Finance & Ops Summary -->
        <section class="grid gap-5 xl:grid-cols-3">
            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-red-500"></span> Returns Overview</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>Sales returns</dt><dd class="font-bold">{{ number_format($this->returnsOverview['sales_returns'], 2) }} EGP</dd></div>
                    <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>Purchase returns</dt><dd class="font-bold">{{ number_format($this->returnsOverview['purchase_returns'], 2) }} EGP</dd></div>
                    <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>Return rate</dt><dd class="font-bold">{{ number_format($this->returnsOverview['return_rate'], 2) }}%</dd></div>
                </dl>
                <div class="mt-5 space-y-2">
                    @forelse ($this->returnsOverview['top_products'] as $row)
                        <div class="flex justify-between rounded-xl border border-slate-100/70 bg-white/40 p-3 text-sm backdrop-blur-sm dark:border-zinc-800 dark:bg-zinc-950/40">
                            <span>{{ $row->productVariant?->product?->product_name ?? 'Unknown' }}</span>
                            <span class="font-bold">{{ (int) $row->qty }} pcs</span>
                        </div>
                    @empty
                        <p class="rounded-xl border border-dashed border-slate-200 p-4 text-center text-sm text-slate-500 dark:border-zinc-800">No returned products.</p>
                    @endforelse
                </div>
            </div>

            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-purple-500"></span> Finance Overview</h2>
                <dl class="mt-4 space-y-3 text-sm">
                    @foreach ([
                        ['Gross sales', $this->finance['gross_sales']],
                        ['Discounts', $this->finance['discounts']],
                        ['Returns', $this->finance['returns']],
                        ['Net sales', $this->finance['net_sales']],
                        ['Cost', $this->finance['cost']],
                        ['Gross profit', $this->finance['gross_profit']],
                        ['Expenses', $this->finance['expenses']],
                        ['Estimated net profit', $this->finance['estimated_net_profit']],
                    ] as [$label, $value])
                        <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 dark:bg-zinc-950/50"><dt>{{ $label }}</dt><dd class="font-bold">{{ number_format($value, 2) }} EGP</dd></div>
                    @endforeach
                    <div class="flex justify-between rounded-xl bg-gradient-to-r from-blue-50 to-indigo-50 p-3 font-bold text-blue-700 dark:from-blue-950/30 dark:to-indigo-950/20 dark:text-blue-300"><dt>Profit margin</dt><dd class="font-black">{{ number_format($this->finance['profit_margin'], 2) }}%</dd></div>
                </dl>
            </div>

            <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-orange-500"></span> Operations Summary</h2>
                <div class="mt-4 space-y-6">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400">Inventory adjustments</h3>
                        <div class="mt-2 space-y-2">
                            @forelse ($this->inventoryOverview['adjustments'] as $row)
                                <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 text-sm dark:bg-zinc-950/50"><span>{{ str($row->adjustment_type)->headline() }}</span><strong>{{ $row->total }} / {{ (int) $row->qty }} pcs</strong></div>
                            @empty
                                <p class="text-sm text-slate-500">No adjustments.</p>
                            @endforelse
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-500 dark:text-slate-400">Stock transfers</h3>
                        <div class="mt-2 space-y-2">
                            @forelse ($this->inventoryOverview['transfers'] as $row)
                                <div class="flex justify-between rounded-xl bg-slate-50/70 p-3 text-sm dark:bg-zinc-950/50"><span>{{ ucfirst($row->status) }}</span><strong>{{ $row->total }}</strong></div>
                            @empty
                                <p class="text-sm text-slate-500">No transfers.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Recent Activity -->
        <section class="grid gap-5 xl:grid-cols-2">
            @foreach ([
                'Latest Sales Invoices' => [$this->recentActivity['sales'], fn ($row) => [$row->invoice_number, $row->customer?->customer_name ?? 'Walk-in', number_format((float) $row->net_total, 2).' EGP', $row->created_at?->format('Y-m-d H:i')]],
                'Latest Online Orders' => [$this->recentActivity['orders'], fn ($row) => [$row->order_number, $row->customer_name, ucfirst($row->status), $row->created_at?->format('Y-m-d H:i')]],
                'Latest Purchase Invoices' => [$this->recentActivity['purchases'], fn ($row) => [$row->invoice_number ?? 'PI-'.$row->id, $row->supplier?->supplier_name ?? 'Unknown', number_format((float) $row->total_amount, 2).' EGP', $row->purchase_invoice_date?->format('Y-m-d')]],
                'Latest Stock Movements' => [$this->recentActivity['movements'], fn ($row) => [$row->productVariant?->product?->product_name ?? 'Unknown', ucfirst($row->movement_type).' '.$row->direction, (int) $row->quantity.' pcs', $row->movement_date?->format('Y-m-d H:i')]],
                'Latest Sales Returns' => [$this->recentActivity['sales_returns'], fn ($row) => [$row->return_number, $row->invoice?->customer?->customer_name ?? 'Unknown', number_format((float) $row->return_amount, 2).' EGP', ucfirst($row->status)]],
                'Latest Supplier Payments' => [$this->recentActivity['supplier_payments'], fn ($row) => [$row->payment_number, $row->supplier?->supplier_name ?? 'Unknown', number_format((float) $row->amount, 2).' EGP', $row->payment_date?->format('Y-m-d')]],
            ] as $title => [$rows, $mapper])
                <div class="rounded-2xl border border-white/20 bg-white/70 p-6 shadow-lg backdrop-blur-md dark:border-zinc-800/50 dark:bg-zinc-900/80">
                    <h2 class="text-lg font-bold flex items-center gap-2"><span class="h-2 w-2 rounded-full bg-slate-500"></span> {{ $title }}</h2>
                    <div class="mt-4 divide-y divide-slate-100/70 dark:divide-zinc-800/50">
                        @forelse ($rows as $row)
                            @php([$primary, $secondary, $meta, $date] = $mapper($row))
                            <div class="grid grid-cols-[1fr_auto] gap-4 py-4 text-sm">
                                <div>
                                    <p class="font-semibold text-slate-800 dark:text-white">{{ $primary }}</p>
                                    <p class="text-slate-500 dark:text-slate-400">{{ $secondary }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-slate-700 dark:text-slate-200">{{ $meta }}</p>
                                    <p class="text-xs text-slate-400 dark:text-slate-500">{{ $date }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="py-6 text-center text-sm text-slate-500 dark:text-slate-400">No activity for the selected filters.</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </section>
    </div>

    <!-- Chart.js Script (unchanged) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.dashboardChartData = @json($this->chartPayload);

        window.initDashboardCharts = function () {
            if (window.dashboardChartsReady) return;
            window.dashboardChartsReady = true;
            const charts = window.dashboardCharts || {};
            window.dashboardCharts = charts;
            const colors = ['#6366f1', '#10b981', '#f43f5e', '#8b5cf6', '#f59e0b', '#06b6d4', '#64748b', '#ef4444'];

            function chartOptions(horizontal = false) {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    indexAxis: horizontal ? 'y' : 'x',
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: 'rgba(148,163,184,.16)' }, ticks: { color: '#64748b' } },
                        y: { grid: { color: 'rgba(148,163,184,.16)' }, ticks: { color: '#64748b' } },
                    },
                };
            }

            function upsert(id, type, labels, values, horizontal = false) {
                const canvas = document.getElementById(id);
                if (!canvas || typeof Chart === 'undefined') return;

                if (charts[id]) charts[id].destroy();
                charts[id] = new Chart(canvas, {
                    type,
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            borderColor: colors[0],
                            backgroundColor: type === 'line' ? 'rgba(99,102,241,.14)' : colors,
                            fill: type === 'line',
                            tension: .35,
                            borderWidth: 2,
                        }],
                    },
                    options: type === 'doughnut' ? { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } } : chartOptions(horizontal),
                });
            }

            function render(data) {
                data = data || {};
                data.salesTrend ??= { labels: [], values: [] };
                data.channels ??= { labels: [], values: [] };
                data.salesByBranch ??= { labels: [], values: [] };
                data.payments ??= { labels: [], values: [] };
                data.topProducts ??= { labels: [], values: [] };
                data.topCustomers ??= { labels: [], values: [] };

                upsert('salesTrendChart', 'line', data.salesTrend.labels, data.salesTrend.values);
                upsert('channelChart', 'doughnut', data.channels.labels, data.channels.values);
                upsert('branchChart', 'bar', data.salesByBranch.labels, data.salesByBranch.values, true);
                upsert('paymentChart', 'doughnut', data.payments.labels, data.payments.values);
                upsert('productChart', 'bar', data.topProducts.labels, data.topProducts.values, true);
                upsert('customerChart', 'bar', data.topCustomers.labels, data.topCustomers.values, true);
            }

            window.renderDashboardCharts = render;
            render(window.dashboardChartData || {});

            window.Livewire?.on('dashboardChartsUpdated', (event) => {
                const payload = Array.isArray(event) ? event[0]?.chartData : event.chartData;
                render(payload || {});
            });
        };

        if (window.Livewire) {
            window.initDashboardCharts();
        } else {
            document.addEventListener('livewire:init', window.initDashboardCharts, { once: true });
        }
    </script>
</div>
