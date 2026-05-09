<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Supplier;
use App\Models\PurchaseInvoice;
use App\Models\User;
use App\Models\PurchaseInvoiceDetail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

new #[Title('إدارة فواتير المشتريات')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'purchase_invoice_date';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    // Fixed: Corrected property name and eager loading
    public function getPurchaseInvoicesProperty()
    {
        return PurchaseInvoice::query()
            ->with(['supplier', 'user', 'paymentMethod'])
            ->when($this->search, function (Builder $q) {
                $q->where(function (Builder $query) {
                    $query
                        ->where('payment_method_name', 'like', "%{$this->search}%")
                        ->orWhere('invoice_number', 'like', "%{$this->search}%")
                        ->orWhereHas('supplier', function (Builder $q) {
                            $q->where('supplier_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    // Fixed: Correct method names and statistics
    public function getTotalPurchaseInvoicesCountProperty()
    {
        return PurchaseInvoice::query()->count();
    }

    public function getTotalPurchaseInvoicesSumProperty()
    {
        return PurchaseInvoice::query()->sum('total_amount') ?? 0;
    }

    public function getAveragePurchaseInvoiceCostProperty()
    {
        return PurchaseInvoice::query()->avg('total_amount') ?? 0;
    }

    public function getCurrentMonthPurchaseInvoicesCountProperty()
    {
        return PurchaseInvoice::query()->whereMonth('purchase_invoice_date', now()->month)->whereYear('purchase_invoice_date', now()->year)->count();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function getSortIcon(string $column): string
    {
        if ($this->sortBy !== $column) {
            return '<svg class="w-3.5 h-3.5 inline opacity-40 group-hover:opacity-100 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>';
        }
        return $this->sortDirection === 'asc' ? '<svg class="w-3.5 h-3.5 inline text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>' : '<svg class="w-3.5 h-3.5 inline text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';
    }

    public function edit(int $id): void
    {
        $this->dispatch('editPurchaseInvoice', id: $id);
    }

    public function delete(int $id): void
    {
        try {
            $purchaseInvoice = PurchaseInvoice::findOrFail($id);
            $purchaseInvoice->delete();
            $this->dispatch('notify', message: 'تم حذف فاتورة المشتريات بنجاح', type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'حدث خطأ أثناء حذف فاتورة المشتريات', type: 'error');
        }

        $this->resetPage();
    }

    // In your Livewire component
    public function redirectToCreateInvoice()
    {
        return redirect()->route('createpurchaseInvoices');
    }
};
?>

<div dir="rtl"
    class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-gray-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-950 py-8 px-4 sm:px-6 lg:px-8 font-sans">

    {{-- Toast Notification --}}
    <div x-data="{ show: false, message: '', type: '' }"
        @notify.window="show = true; message = $event.detail.message; type = $event.detail.type; setTimeout(() => show = false, 4000)"
        x-show="show" x-transition.duration.300ms
        class="fixed top-24 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-md shadow-2xl rounded-xl"
        style="display: none;">
        <div x-show="type === 'success'"
            class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white p-4 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="flex-1 font-medium" x-text="message"></span>
        </div>
        <div x-show="type === 'error'"
            class="bg-gradient-to-r from-red-500 to-rose-500 text-white p-4 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="flex-1 font-medium" x-text="message"></span>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-10">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-5">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-10 h-1 bg-gradient-to-l from-emerald-500 to-teal-500 rounded-full"></div>
                        <span
                            class="text-emerald-600 dark:text-emerald-400 font-medium text-sm tracking-wider">PURCHASES
                            MANAGEMENT</span>
                    </div>
                    <h1
                        class="text-4xl font-bold bg-gradient-to-l from-slate-900 to-slate-600 dark:from-white dark:to-slate-400 bg-clip-text text-transparent">
                        فواتير المشتريات
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm max-w-lg">
                        لوحة إدارة وعرض وتتبع جميع فواتير المشتريات في النظام مع إحصائيات دقيقة
                    </p>
                </div>

                <flux:modal.trigger name="add-purchaseInvoice">
                    <button wire:click="redirectToCreateInvoice"
                        class="group relative overflow-hidden bg-gradient-to-r from-emerald-600 to-teal-600 text-white px-6 py-3 rounded-xl shadow-lg hover:shadow-emerald-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-95">
                        <span class="relative z-10 flex items-center gap-2 font-medium">
                            <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-300" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                            إضافة فاتورة مشتريات جديدة
                        </span>
                        <div
                            class="absolute inset-0 -translate-x-full group-hover:translate-x-0 transition-transform duration-500 bg-gradient-to-r from-emerald-500 to-teal-500">
                        </div>
                    </button>
                </flux:modal.trigger>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <!-- Total purchase Invoice Card -->
            <div
                class="group relative overflow-hidden bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                <div
                    class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-blue-500 to-blue-600 rounded-l-2xl">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-2.5 rounded-xl bg-blue-100 dark:bg-blue-900/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/50 px-2.5 py-1 rounded-full">إجمالي</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ number_format($this->TotalPurchaseInvoicesCount) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5">إجمالي عدد فواتير المشتريات</p>
                </div>
            </div>

            <!-- Total Amount Card -->
            <div
                class="group relative overflow-hidden bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                <div
                    class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-emerald-500 to-emerald-600 rounded-l-2xl">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-2.5 rounded-xl bg-emerald-100 dark:bg-emerald-900/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-medium text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/50 px-2.5 py-1 rounded-full">المبلغ</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ number_format($this->TotalPurchaseInvoicesSum, 2) }} <span
                            class="text-sm font-normal text-gray-500">ج.م</span>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5">إجمالي مبلغ فواتير المشتريات</p>
                </div>
            </div>

            <!-- Average Card -->
            <div
                class="group relative overflow-hidden bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                <div
                    class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-purple-500 to-purple-600 rounded-l-2xl">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-2.5 rounded-xl bg-purple-100 dark:bg-purple-900/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-medium text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/50 px-2.5 py-1 rounded-full">المتوسط</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ number_format($this->averagePurchaseInvoiceCost, 2) }} <span
                            class="text-sm font-normal text-gray-500">ج.م</span>
                    </p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5">متوسط تكلفة فاتورة المشتريات</p>
                </div>
            </div>

            <!-- Current Month Card -->
            <div
                class="group relative overflow-hidden bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
                <div
                    class="absolute top-0 left-0 w-1.5 h-full bg-gradient-to-b from-amber-500 to-orange-600 rounded-l-2xl">
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div
                            class="p-2.5 rounded-xl bg-amber-100 dark:bg-amber-900/30 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <span
                            class="text-xs font-medium text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/50 px-2.5 py-1 rounded-full">{{ now()->translatedFormat('F') }}</span>
                    </div>
                    <p class="text-3xl font-bold text-gray-800 dark:text-white">
                        {{ number_format($this->currentMonthPurchaseInvoicesCount) }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1.5">فواتير المشتريات هذا الشهر</p>
                </div>
            </div>
        </div>
    </div>



    {{-- Main Table Card --}}
    <div class="max-w-7xl mx-auto">
        <div
            class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl shadow-xl overflow-hidden border border-gray-100 dark:border-gray-700">

            {{-- Loading Overlay --}}
            <div wire:loading.flex class="fixed inset-0 bg-black/40 backdrop-blur-md items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-8 shadow-2xl flex flex-col items-center gap-5">
                    <div class="relative">
                        <div
                            class="animate-spin rounded-full h-14 w-14 border-4 border-emerald-600 border-t-transparent">
                        </div>
                        <div class="absolute inset-0 rounded-full h-14 w-14 border-4 border-emerald-600/20"></div>
                    </div>
                    <p class="text-gray-600 dark:text-gray-300 font-medium">جاري تحميل البيانات...</p>
                </div>
            </div>

            {{-- Search and Filters Bar --}}
            <div
                class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50/50 to-white dark:from-gray-900/50 dark:to-gray-800/50">
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="relative w-full sm:w-96 group">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400 group-focus-within:text-emerald-500 transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="🔍 ابحث عن فاتورة مشتريات برقم الفاتورة، المورد، أو طريقة الدفع..."
                            class="w-full pr-10 pl-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-transparent dark:text-white transition-all" />
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="text-sm text-gray-500 dark:text-gray-400">عرض</span>
                        <select wire:model.live="perPage"
                            class="border border-gray-200 dark:border-gray-600 rounded-xl px-3 py-2 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 focus:ring-2 focus:ring-emerald-500 cursor-pointer">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="text-sm text-gray-500 dark:text-gray-400">نتيجة</span>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100/80 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
                            <th class="p-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-200/50 dark:hover:bg-gray-600/50 transition group"
                                wire:click="sortByColumn('id')">
                                <div class="flex items-center justify-between gap-2">
                                    #
                                    <span
                                        class="transition-transform group-hover:scale-110">{!! $this->getSortIcon('id') !!}</span>
                                </div>
                            </th>
                            <th
                                class="p-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                رقم الفاتورة
                            </th>
                            <th class="p-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-200/50 dark:hover:bg-gray-600/50 transition group"
                                wire:click="sortByColumn('supplier_id')">
                                <div class="flex items-center justify-between gap-2">
                                    المورد
                                    <span
                                        class="transition-transform group-hover:scale-110">{!! $this->getSortIcon('supplier_id') !!}</span>
                                </div>
                            </th>
                            <th
                                class="p-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                إجمالي الفاتورة
                            </th>
                            <th
                                class="p-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                طريقة الدفع
                            </th>
                            <th
                                class="p-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                المبلغ المتبقي
                            </th>
                            <th class="p-4 text-right text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-200/50 dark:hover:bg-gray-600/50 transition group"
                                wire:click="sortByColumn('purchase_invoice_date')">
                                <div class="flex items-center justify-between gap-2">
                                    تاريخ الفاتورة
                                    <span
                                        class="transition-transform group-hover:scale-110">{!! $this->getSortIcon('purchase_invoice_date') !!}</span>
                                </div>
                            </th>
                            <th
                                class="p-4 text-center text-xs font-bold text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($this->purchaseInvoices as $purchase)
                            <tr
                                class="hover:bg-gradient-to-r hover:from-emerald-50/30 hover:to-transparent dark:hover:from-emerald-900/20 transition-all duration-300 group">
                                <td class="p-4 text-sm text-gray-500 dark:text-gray-400 font-mono font-bold">
                                    #{{ $loop->iteration + ($this->purchaseInvoices->currentPage() - 1) * $this->purchaseInvoices->perPage() }}
                                </td>
                                <td class="p-4">
                                    <span
                                        class="px-2.5 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-mono font-semibold text-gray-700 dark:text-gray-300">
                                        {{ $purchase->invoice_number ?? 'INV-' . str_pad($purchase->id, 6, '0', STR_PAD_LEFT) }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-xl flex items-center justify-center font-bold shadow-md group-hover:scale-110 transition-transform duration-300 text-sm">
                                            {{ strtoupper(mb_substr($purchase->supplier->supplier_name ?? 'N/A', 0, 1)) }}
                                        </div>
                                        <div>
                                            <p
                                                class="font-semibold text-gray-800 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition">
                                                {{ $purchase->supplier->supplier_name ?? 'غير محدد' }}
                                            </p>
                                            <p class="text-xs text-gray-400">كود: {{ $purchase->supplier_id ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-emerald-600 dark:text-emerald-400 text-lg">
                                        {{ number_format($purchase->total_amount, 2) }}
                                        <span class="text-xs font-normal text-gray-500">ج.م</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span
                                        class="px-3 py-1.5 rounded-lg text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $purchase->payment_method ?? 'نقدي' }}
                                    </span>
                                </td>
                                <td class="p-4">
                                    <div class="font-bold text-amber-600 dark:text-amber-400">
                                        {{ number_format($purchase->remaining_amount ?? 0, 2) }}
                                        <span class="text-xs font-normal text-gray-500">ج.م</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="text-sm">
                                        <div class="text-gray-700 dark:text-gray-300 font-medium dir-ltr text-left">
                                            {{ $purchase->purchase_invoice_date ? $purchase->purchase_invoice_date->format('Y/m/d') : 'غير محدد' }}
                                        </div>
                                        <div class="text-xs text-gray-400 mt-0.5">
                                            {{ $purchase->purchase_invoice_date ? $purchase->purchase_invoice_date->diffForHumans() : '' }}
                                        </div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        @php
                                            $img = $purchase->purchase_invoice_image ?? null;
                                            $previewUrl = null;
                                            if (is_string($img) && $img !== '') {
                                                $s = preg_replace(
                                                    '#^(storage/app/public/|/storage/|storage/|public/)#',
                                                    '',
                                                    $img,
                                                );
                                                if (\Illuminate\Support\Facades\Storage::disk('public')->exists($s)) {
                                                    $previewUrl = \Illuminate\Support\Facades\Storage::disk(
                                                        'public',
                                                    )->url($s);
                                                } elseif (file_exists(public_path('storage/' . $s))) {
                                                    $previewUrl = asset('storage/' . $s);
                                                }
                                            }
                                        @endphp
                                        @if ($previewUrl)
                                            <a href="{{ $previewUrl }}" target="_blank" title="عرض الصورة"
                                                class="p-2 text-gray-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7-5 7 5v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8zM3 8l9 6 9-6" />
                                                </svg>
                                            </a>
                                        @endif
                                        <button wire:click="edit({{ $purchase->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button wire:click="delete({{ $purchase->id }})"
                                            wire:confirm="هل أنت متأكد من حذف فاتورة المشتريات رقم '{{ $purchase->invoice_number ?? $purchase->id }}'؟"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200 hover:scale-110 active:scale-95">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-16 text-center">
                                    <div class="flex flex-col items-center gap-5">
                                        <div
                                            class="w-28 h-28 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-full flex items-center justify-center">
                                            <svg class="w-14 h-14 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-xl font-semibold text-gray-600 dark:text-gray-400">لا توجد
                                                فواتير مشتريات</p>
                                            <p class="text-sm text-gray-400 mt-2">ابدأ بإضافة فاتورة مشتريات جديدة
                                                للنظام</p>
                                        </div>
                                        <flux:modal.trigger name="add-purchaseInvoice">
                                            <button
                                                class="mt-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-xl hover:shadow-lg transition-all hover:scale-105">
                                                + إضافة فاتورة مشتريات جديدة
                                            </button>
                                        </flux:modal.trigger>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($this->purchaseInvoices->hasPages())
                <div
                    class="p-5 border-t border-gray-100 dark:border-gray-700 bg-gradient-to-r from-gray-50/50 to-white dark:from-gray-900/50 dark:to-gray-800/50">
                    {{ $this->purchaseInvoices->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @keyframes fade-in-down {
        from {
            opacity: 0;
            transform: translate(-50%, -20px);
        }

        to {
            opacity: 1;
            transform: translate(-50%, 0);
        }
    }

    .animate-fade-in-down {
        animation: fade-in-down 0.3s ease-out;
    }

    .dir-ltr {
        direction: ltr;
    }

    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
