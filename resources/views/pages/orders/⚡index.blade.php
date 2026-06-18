<?php

use App\Models\OnlineOrder;
use App\Models\Shipping;
use App\Services\DiscountService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

new #[Title('Online Orders')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $status = '';
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $governorate = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;
    public ?int $selectedOrderId = null;
    public array $statusUpdates = [];

    public function getOrdersProperty()
    {
        $dateFrom = $this->validDate($this->dateFrom);
        $dateTo = $this->validDate($this->dateTo);

        $orders = OnlineOrder::query()
            ->with(['shipping', 'coupon', 'salesInvoice.paymentMethod'])
            ->when($this->search, function ($query) {
                $search = trim($this->search);

                $query->where(function ($query) use ($search) {
                    $query->where('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%")
                        ->orWhereHas('salesInvoice', fn ($query) => $query->where('invoice_number', 'like', "%{$search}%"));
                });
            })
            ->when($this->status, fn ($query) => $query->where('status', $this->status))
            ->when($dateFrom, fn ($query) => $query->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($query) => $query->whereDate('created_at', '<=', $dateTo))
            ->when($this->governorate, fn ($query) => $query->where('shipping_id', $this->governorate))
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);

        $orders->getCollection()->each(function (OnlineOrder $order) {
            $this->statusUpdates[$order->id] ??= $order->status;
        });

        return $orders;
    }

    public function getSelectedOrderProperty(): ?OnlineOrder
    {
        if (! $this->selectedOrderId) {
            return null;
        }

        return OnlineOrder::query()
            ->with([
                'shipping',
                'coupon',
                'salesInvoice.paymentMethod',
                'salesInvoice.salesInvoiceDetails.productVariant.product.images',
                'salesInvoice.salesInvoiceDetails.productVariant.product.primaryImage',
                'salesInvoice.salesInvoiceDetails.productVariant.color',
                'salesInvoice.salesInvoiceDetails.productVariant.size',
            ])
            ->find($this->selectedOrderId);
    }

    public function getGovernoratesProperty()
    {
        return Shipping::query()->orderBy('city_name')->get(['id', 'city_name']);
    }

    public function getStatusesProperty(): array
    {
        return OnlineOrder::STATUSES;
    }

    public function getPendingOrdersCountProperty(): int
    {
        return OnlineOrder::query()->where('status', 'pending')->count();
    }

    public function getTodayOrdersCountProperty(): int
    {
        return OnlineOrder::query()->whereDate('created_at', today())->count();
    }

    public function getRevenueInQueueProperty(): float
    {
        return (float) OnlineOrder::query()
            ->join('sales_invoices', 'online_orders.sales_invoice_id', '=', 'sales_invoices.id')
            ->whereIn('online_orders.status', ['pending', 'confirmed', 'preparing'])
            ->sum('sales_invoices.net_total');
    }

    public function updated($property): void
    {
        if (in_array($property, ['search', 'status', 'dateFrom', 'dateTo', 'governorate', 'perPage'], true)) {
            $this->resetPage();
        }
    }

    public function sortByColumn(string $column): void
    {
        if (! in_array($column, ['created_at', 'status', 'customer_name', 'shipping_cost'], true)) {
            return;
        }

        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function showDetails(int $orderId): void
    {
        abort_unless(auth()->user()?->can('orders.view'), 403);

        $this->selectedOrderId = $orderId;
    }

    public function closeDetails(): void
    {
        $this->selectedOrderId = null;
    }

    public function updateStatus(int $orderId): void
    {
        abort_unless(auth()->user()?->can('orders.update'), 403);

        $status = $this->statusUpdates[$orderId] ?? null;

        $this->validate([
            "statusUpdates.{$orderId}" => ['required', 'in:'.implode(',', OnlineOrder::STATUSES)],
        ], [
            "statusUpdates.{$orderId}.in" => 'Please choose a valid order status.',
        ]);

        $order = OnlineOrder::query()->with('coupon')->findOrFail($orderId);
        $oldStatus = $order->status;

        if (in_array($status, ['confirmed', 'preparing', 'shipped', 'delivered'], true) && ! $order->coupon_counted_at && $order->coupon) {
            app(DiscountService::class)->incrementCouponUsage($order->coupon);
            $order->coupon_counted_at = now();
        }

        if (in_array($status, ['cancelled', 'returned'], true) && $order->coupon_counted_at && $order->coupon) {
            app(DiscountService::class)->decrementCouponUsage($order->coupon);
            $order->coupon_counted_at = null;
        }

        $order->status = $status;
        $order->save();

        if ($order->salesInvoice && $status === 'cancelled' && $oldStatus !== 'cancelled') {
            $order->salesInvoice->update(['status' => 'cancelled']);
        }

        session()->flash('success', "Order {$order->order_number} status updated to {$status}.");
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'status', 'dateFrom', 'dateTo', 'governorate']);
        $this->resetPage();
    }

    private function validDate(string $date): ?string
    {
        return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) === 1 ? $date : null;
    }
};
?>

<div dir="ltr" class="min-h-screen bg-stone-50 px-4 py-8 text-slate-900 antialiased dark:bg-slate-950 dark:text-white sm:px-6 lg:px-8">
    <x-flash-message />

    <div class="mx-auto max-w-[1700px] space-y-6">
        <!-- Header & Stats -->
        <section class="overflow-hidden rounded-2xl border border-stone-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="bg-gradient-to-br from-white via-white to-rose-50/50 px-6 py-6 dark:from-slate-900 dark:via-slate-900 dark:to-rose-950/20">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-500">Operations Overview</p>
                        <h1 class="mt-1 text-3xl font-bold tracking-tight">Online Orders</h1>
                        <p class="mt-2 max-w-2xl text-sm text-slate-500 dark:text-slate-400">Manage customer orders, fulfillment status, payment totals, and delivery details from one focused queue.</p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-3 lg:min-w-[620px]">
                        <div class="rounded-xl border border-rose-100 bg-rose-50/70 p-4 backdrop-blur dark:border-rose-900/40 dark:bg-rose-950/20">
                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-300">Pending Orders</p>
                            <p class="mt-2 text-3xl font-bold">{{ number_format($this->pendingOrdersCount) }}</p>
                        </div>
                        <div class="rounded-xl border border-slate-200 bg-white/80 p-4 backdrop-blur dark:border-slate-800 dark:bg-slate-900/80">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Today's Orders</p>
                            <p class="mt-2 text-3xl font-bold">{{ number_format($this->todayOrdersCount) }}</p>
                        </div>
                        <div class="rounded-xl border border-emerald-100 bg-emerald-50/70 p-4 backdrop-blur dark:border-emerald-900/40 dark:bg-emerald-950/20">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-700 dark:text-emerald-300">Queue Value</p>
                            <p class="mt-2 text-2xl font-bold">{{ number_format($this->revenueInQueue, 2) }} EGP</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="grid gap-4 border-t border-stone-100 p-5 dark:border-slate-800 md:grid-cols-2 xl:grid-cols-6">
                <div class="xl:col-span-2">
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Search</label>
                    <div class="relative">
                        <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input wire:model.live.debounce.300ms="search" type="search" placeholder="Order number, customer, phone"
                            class="w-full rounded-xl border border-stone-200 bg-white py-2.5 pl-10 pr-4 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950 dark:focus:ring-rose-950/40">
                    </div>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Status</label>
                    <select wire:model.live="status" class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950">
                        <option value="">All statuses</option>
                        @foreach ($this->statuses as $orderStatus)
                            <option value="{{ $orderStatus }}">{{ ucfirst($orderStatus) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Governorate</label>
                    <select wire:model.live="governorate" class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950">
                        <option value="">All governorates</option>
                        @foreach ($this->governorates as $shipping)
                            <option value="{{ $shipping->id }}">{{ $shipping->city_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Date From</label>
                    <input wire:model.live="dateFrom" type="date" class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950">
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-500">Date To</label>
                    <input wire:model.live="dateTo" type="date" class="w-full rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100 dark:border-slate-700 dark:bg-slate-950">
                </div>
                <div class="flex items-end gap-2 xl:col-span-6">
                    <select wire:model.live="perPage" class="rounded-xl border border-stone-200 bg-white px-3 py-2.5 text-sm shadow-sm outline-none dark:border-slate-700 dark:bg-slate-950">
                        <option value="10">10 per page</option>
                        <option value="25">25 per page</option>
                        <option value="50">50 per page</option>
                    </select>
                    <button wire:click="resetFilters" type="button" class="rounded-xl border border-stone-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-stone-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800">
                        Reset filters
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="relative">
                <div wire:loading.flex class="absolute inset-0 z-20 items-center justify-center bg-white/80 backdrop-blur-sm dark:bg-slate-950/80">
                    <div class="flex items-center gap-3 rounded-xl border border-stone-200 bg-white px-5 py-3 text-sm font-semibold shadow-lg dark:border-slate-800 dark:bg-slate-900">
                        <svg class="h-5 w-5 animate-spin text-rose-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Loading orders...
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full min-w-[1300px] text-sm">
                        <thead class="bg-stone-50/80 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-950/60">
                            <tr>
                                <th class="px-4 py-3.5 text-left">Order #</th>
                                <th wire:click="sortByColumn('customer_name')" class="cursor-pointer px-4 py-3.5 text-left hover:text-rose-600 transition">Customer</th>
                                <th class="px-4 py-3.5 text-left">Phone</th>
                                <th class="px-4 py-3.5 text-left">Governorate</th>
                                <th class="px-4 py-3.5 text-left">Area</th>
                                <th class="px-4 py-3.5 text-right">Subtotal</th>
                                <th wire:click="sortByColumn('shipping_cost')" class="cursor-pointer px-4 py-3.5 text-right hover:text-rose-600 transition">Shipping</th>
                                <th class="px-4 py-3.5 text-right">Discount</th>
                                <th class="px-4 py-3.5 text-right">Total</th>
                                <th class="px-4 py-3.5 text-left">Payment</th>
                                <th wire:click="sortByColumn('status')" class="cursor-pointer px-4 py-3.5 text-left hover:text-rose-600 transition">Status</th>
                                <th wire:click="sortByColumn('created_at')" class="cursor-pointer px-4 py-3.5 text-left hover:text-rose-600 transition">Created</th>
                                <th class="px-4 py-3.5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 dark:divide-slate-800">
                            @forelse ($this->orders as $order)
                                @php
                                    $statusClasses = [
                                        'pending'   => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:ring-amber-900',
                                        'confirmed' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-950/30 dark:text-sky-300 dark:ring-sky-900',
                                        'preparing' => 'bg-indigo-50 text-indigo-700 ring-indigo-200 dark:bg-indigo-950/30 dark:text-indigo-300 dark:ring-indigo-900',
                                        'shipped'   => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-950/30 dark:text-blue-300 dark:ring-blue-900',
                                        'delivered' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:ring-emerald-900',
                                        'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:ring-rose-900',
                                        'returned'  => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
                                    ][$order->status] ?? 'bg-slate-100 text-slate-700 ring-slate-200';
                                @endphp
                                <tr wire:key="order-{{ $order->id }}" class="transition-colors duration-150 hover:bg-rose-50/40 dark:hover:bg-rose-950/10">
                                    <td class="px-4 py-4 font-semibold text-slate-900 dark:text-white">{{ $order->order_number }}</td>
                                    <td class="px-4 py-4 font-medium">{{ $order->customer_name }}</td>
                                    <td class="px-4 py-4 font-mono text-xs text-slate-500">{{ $order->customer_phone }}</td>
                                    <td class="px-4 py-4">{{ $order->shipping?->city_name ?? '-' }}</td>
                                    <td class="px-4 py-4">{{ $order->area }}</td>
                                    <td class="px-4 py-4 text-right font-medium">{{ number_format($order->subtotal, 2) }}</td>
                                    <td class="px-4 py-4 text-right font-medium">{{ number_format($order->shipping_cost, 2) }}</td>
                                    <td class="px-4 py-4 text-right font-medium text-rose-600">
                                        {{ (float) $order->discount_amount > 0 ? '-'.number_format((float) $order->discount_amount, 2) : '-' }}
                                        @if ($order->coupon_code)
                                            <div class="text-[11px] font-semibold text-slate-400">{{ $order->coupon_code }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-right font-bold">{{ number_format($order->grand_total, 2) }}</td>
                                    <td class="px-4 py-4">{{ $order->salesInvoice?->paymentMethod?->payment_method_name ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 {{ $statusClasses }}">
                                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-current"></span>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="font-medium">{{ $order->created_at?->format('Y-m-d') }}</div>
                                        <div class="text-xs text-slate-400">{{ $order->created_at?->format('h:i A') }}</div>
                                    </td>
                                    <td class="px-4 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            @can('orders.update')
                                                <select wire:model="statusUpdates.{{ $order->id }}" class="rounded-lg border border-stone-200 bg-white px-2 py-1.5 text-xs dark:border-slate-700 dark:bg-slate-950">
                                                    @foreach ($this->statuses as $orderStatus)
                                                        <option value="{{ $orderStatus }}">{{ ucfirst($orderStatus) }}</option>
                                                    @endforeach
                                                </select>
                                                <button wire:click="updateStatus({{ $order->id }})" class="rounded-lg bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-600 dark:bg-white dark:text-slate-900 dark:hover:bg-rose-100">
                                                    Save
                                                </button>
                                            @endcan
                                            <button wire:click="showDetails({{ $order->id }})" class="rounded-lg border border-stone-200 bg-white px-3 py-1.5 text-xs font-semibold transition hover:bg-stone-50 dark:border-slate-700 dark:bg-slate-900 dark:hover:bg-slate-800">
                                                Details
                                            </button>
                                        </div>
                                        @error("statusUpdates.{$order->id}") <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" class="px-6 py-20 text-center">
                                        <div class="mx-auto flex max-w-sm flex-col items-center">
                                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-stone-100 text-2xl dark:bg-slate-800">📦</div>
                                            <h3 class="mt-4 text-lg font-bold">No online orders found</h3>
                                            <p class="mt-1 text-sm text-slate-500">Try changing the filters or wait for new checkout orders to arrive.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($this->orders->hasPages())
                    <div class="border-t border-stone-100 px-5 py-4 dark:border-slate-800">
                        {{ $this->orders->links() }}
                    </div>
                @endif
            </div>
        </section>
    </div>

    <!-- Detail Modal -->
    @if ($this->selectedOrder)
        @php $selected = $this->selectedOrder; @endphp
        <div class="fixed inset-0 z-50 flex items-start justify-center overflow-y-auto bg-slate-950/50 px-4 py-8 backdrop-blur-sm" wire:click.self="closeDetails">
            <div class="my-8 w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-slate-900" @click.stop>
                <!-- Modal header -->
                <div class="flex items-start justify-between gap-4 border-b border-stone-100 px-6 py-5 dark:border-slate-800">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-rose-500">Order Details</p>
                        <h2 class="mt-1 text-2xl font-bold">{{ $selected->order_number }}</h2>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-0.5 text-xs font-semibold ring-1 mt-2 {{
                            [
                                'pending'   => 'bg-amber-50 text-amber-700 ring-amber-200 dark:bg-amber-950/30 dark:text-amber-300 dark:ring-amber-900',
                                'confirmed' => 'bg-sky-50 text-sky-700 ring-sky-200 dark:bg-sky-950/30 dark:text-sky-300 dark:ring-sky-900',
                                'preparing' => 'bg-indigo-50 text-indigo-700 ring-indigo-200 dark:bg-indigo-950/30 dark:text-indigo-300 dark:ring-indigo-900',
                                'shipped'   => 'bg-blue-50 text-blue-700 ring-blue-200 dark:bg-blue-950/30 dark:text-blue-300 dark:ring-blue-900',
                                'delivered' => 'bg-emerald-50 text-emerald-700 ring-emerald-200 dark:bg-emerald-950/30 dark:text-emerald-300 dark:ring-emerald-900',
                                'cancelled' => 'bg-rose-50 text-rose-700 ring-rose-200 dark:bg-rose-950/30 dark:text-rose-300 dark:ring-rose-900',
                                'returned'  => 'bg-slate-100 text-slate-700 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700',
                            ][$selected->status] ?? 'bg-slate-100 text-slate-700 ring-slate-200'
                        }}">
                            <span class="inline-block h-1.5 w-1.5 rounded-full bg-current"></span>
                            {{ ucfirst($selected->status) }}
                        </span>
                    </div>
                    <button wire:click="closeDetails" class="rounded-xl border border-stone-200 bg-white p-2 text-slate-500 hover:bg-stone-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-400 dark:hover:bg-slate-800">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <!-- Modal body -->
                <div class="grid gap-6 p-6 lg:grid-cols-3">
                    <!-- Customer info card -->
                    <div class="rounded-xl border border-stone-200 bg-stone-50/50 p-5 dark:border-slate-800 dark:bg-slate-950/50">
                        <h3 class="text-sm font-semibold uppercase tracking-wide text-slate-500">Customer Information</h3>
                        <dl class="mt-4 space-y-4 text-sm">
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Name</dt><dd class="mt-0.5 font-semibold">{{ $selected->customer_name }}</dd></div>
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Phone</dt><dd class="mt-0.5 font-mono text-xs">{{ $selected->customer_phone }}</dd></div>
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Email</dt><dd class="mt-0.5">{{ $selected->customer_email ?: '-' }}</dd></div>
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Governorate</dt><dd class="mt-0.5">{{ $selected->shipping?->city_name ?? '-' }}</dd></div>
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Area</dt><dd class="mt-0.5">{{ $selected->area }}</dd></div>
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Full Address</dt><dd class="mt-0.5">{{ $selected->address }}</dd></div>
                            <div><dt class="text-xs font-medium uppercase tracking-wide text-slate-400">Order Notes</dt><dd class="mt-0.5">{{ $selected->order_note ?: '-' }}</dd></div>
                        </dl>
                    </div>

                    <!-- Items & totals -->
                    <div class="lg:col-span-2 space-y-5">
                        <div class="overflow-hidden rounded-xl border border-stone-200 dark:border-slate-800">
                            <table class="w-full text-sm">
                                <thead class="bg-stone-50 text-xs font-semibold uppercase tracking-wide text-slate-500 dark:bg-slate-950/60">
                                    <tr>
                                        <th class="px-4 py-3 text-left">Product</th>
                                        <th class="px-4 py-3 text-left">Color</th>
                                        <th class="px-4 py-3 text-left">Size</th>
                                        <th class="px-4 py-3 text-right">Qty</th>
                                        <th class="px-4 py-3 text-right">Unit Price</th>
                                        <th class="px-4 py-3 text-right">Line Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-stone-100 dark:divide-slate-800">
                                    @foreach ($selected->salesInvoice?->salesInvoiceDetails ?? [] as $item)
                                        @php
                                            $variant = $item->productVariant;
                                            $product = $variant?->product;
                                            $image = $product?->images?->firstWhere('color_id', $variant?->color_id) ?? $product?->primaryImage ?? $product?->images?->first();
                                            $lineTotal = (float) $item->unit_price * (int) $item->product_quantity;
                                        @endphp
                                        <tr>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    @if ($image?->image_path)
                                                        <img src="{{ Storage::url($image->image_path) }}" alt="{{ $product?->product_name }}" class="h-14 w-14 rounded-xl object-cover ring-1 ring-stone-200 dark:ring-slate-700">
                                                    @else
                                                        <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-stone-100 text-xs font-bold text-slate-500 ring-1 ring-stone-200 dark:bg-slate-800 dark:ring-slate-700">IMG</div>
                                                    @endif
                                                    <div>
                                                        <p class="font-semibold">{{ $product?->product_name ?? 'Deleted product' }}</p>
                                                        <p class="text-xs text-slate-400">{{ $variant?->sku }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4">{{ $variant?->color?->color_name ?? '-' }}</td>
                                            <td class="px-4 py-4">{{ $variant?->size?->size_name ?? '-' }}</td>
                                            <td class="px-4 py-4 text-right">{{ number_format($item->product_quantity) }}</td>
                                            <td class="px-4 py-4 text-right">{{ number_format($item->unit_price, 2) }}</td>
                                            <td class="px-4 py-4 text-right font-bold">{{ number_format($lineTotal, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Totals card -->
                        <div class="flex justify-end">
                            <div class="w-full max-w-sm rounded-xl border border-stone-200 bg-stone-50/50 p-5 dark:border-slate-800 dark:bg-slate-950/50">
                                <div class="flex justify-between py-2 text-sm"><span class="text-slate-500">Subtotal</span><span class="font-semibold">{{ number_format($selected->subtotal, 2) }} EGP</span></div>
                                @if ((float) $selected->discount_amount > 0)
                                    <div class="flex justify-between py-2 text-sm">
                                        <span class="text-slate-500">Coupon Discount {{ $selected->coupon_code ? '('.$selected->coupon_code.')' : '' }}</span>
                                        <span class="font-semibold text-rose-600">-{{ number_format((float) $selected->discount_amount, 2) }} EGP</span>
                                    </div>
                                @endif
                                <div class="flex justify-between py-2 text-sm"><span class="text-slate-500">Shipping Cost</span><span class="font-semibold">{{ number_format($selected->shipping_cost, 2) }} EGP</span></div>
                                <div class="mt-3 flex justify-between border-t border-stone-200 pt-4 text-lg font-bold dark:border-slate-800">
                                    <span>Grand Total</span>
                                    <span>{{ number_format($selected->grand_total, 2) }} EGP</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
