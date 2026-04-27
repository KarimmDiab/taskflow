<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Product;

new #[Title('إدارة المنتجات')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    public function getProductsProperty()
    {
        return Product::with(['category', 'branch'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query
                        ->where('product_name', 'like', "%{$this->search}%")

                        ->orWhereHas('category', function ($q) {
                            $q->where('category_name', 'like', "%{$this->search}%");
                        })

                        ->orWhereHas('branch', function ($q) {
                            $q->where('branch_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getTotalProductsProperty()
    {
        return Product::count();
    }

    public function getLowStockCountProperty()
    {
        return Product::where('product_quantity', '<', 10)->count();
    }

    public function getOutOfStockCountProperty()
    {
        return Product::where('product_quantity', 0)->count();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function sortByColumn($column)
    {
        if ($this->sortBy === $column) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
    }

    public function getSortIcon($column)
    {
        if ($this->sortBy !== $column) {
            return '↑';
        }
        return $this->sortDirection === 'asc' ? '↑' : '↓';
    }

    public function edit($id)
    {
        $this->dispatch('editProduct', id: $id);
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        $productName = $product->product_name;
        $product->delete();

        session()->flash('success', "تم حذف المنتج '{$productName}' بنجاح");
        $this->resetPage();
    }
};
?>

<div dir="rtl"
    class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 py-8 px-4 sm:px-6 lg:px-8">

    <x-flash-message />

    {{-- Header Section with Stats --}}
    <div class="max-w-7xl mx-auto mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1
                    class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">
                    إدارة المنتجات
                </h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    إدارة وعرض وتعديل جميع المنتجات في نظامك
                </p>
            </div>

            <flux:modal.trigger name="add-product">
                <button
                    class="group relative bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-3 rounded-xl hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5 group-hover:rotate-90 transition-transform duration-200" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        إضافة منتج جديد
                    </span>
                </button>
            </flux:modal.trigger>
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border-r-4 border-blue-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">إجمالي المنتجات</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $this->totalProducts }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border-r-4 border-green-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">متوسط السعر</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">
                            {{ number_format(Product::avg('product_price') ?? 0, 0) }} ج.م
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border-r-4 border-yellow-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">منتجات منخفضة</p>
                        <p class="text-gray-300 dark:text-gray-300 text-sm">اقل من 10 قطع</p>

                        <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $this->lowStockCount }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-4 border-r-4 border-red-500 hover:shadow-xl transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">نفذت الكمية</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-white mt-1">{{ $this->outOfStockCount }}
                        </p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <livewire:product.create />
    <livewire:product.edit />

    {{-- Main Table Card --}}
    <div class="max-w-7xl mx-auto">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">

            {{-- Loading Overlay --}}
            <div wire:loading.flex class="fixed inset-0 bg-black/20 backdrop-blur-sm items-center justify-center z-50">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-2xl flex flex-col items-center gap-4">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent">
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">جاري التحميل...</p>
                </div>
            </div>

            {{-- Search and Filters Bar --}}
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="relative w-full sm:w-96">
                        <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="ابحث عن منتج..."
                            class="w-full pr-10 pl-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all" />
                    </div>

                    <div class="flex gap-2">
                        <select wire:model.live="perPage"
                            class="border border-gray-300 dark:border-gray-600 rounded-xl px-3 py-2 dark:bg-gray-700 dark:text-white">
                            <option value="10">10 منتجات</option>
                            <option value="25">25 منتج</option>
                            <option value="50">50 منتج</option>
                            <option value="100">100 منتج</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                        <tr>
                            <th class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                wire:click="sortByColumn('id')">
                                <div class="flex items-center justify-between gap-2">
                                    # <span class="text-lg">{{ $this->getSortIcon('id') }}</span>
                                </div>
                            </th>
                            <th class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                wire:click="sortByColumn('product_name')">
                                <div class="flex items-center justify-between gap-2">
                                    اسم المنتج <span class="text-lg">{{ $this->getSortIcon('product_name') }}</span>
                                </div>
                            </th>
                            <th
                                class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                الكمية</th>
                            <th
                                class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                السعر</th>
                            <th
                                class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                التصنيف</th>
                            <th
                                class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                الفرع</th>
                            <th class="p-4 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600 transition"
                                wire:click="sortByColumn('created_at')">
                                <div class="flex items-center justify-between gap-2">
                                    تاريخ الإضافة <span class="text-lg">{{ $this->getSortIcon('created_at') }}</span>
                                </div>
                            </th>
                            <th
                                class="p-4 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($this->products as $product)
                            <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all duration-200 group">
                                <td class="p-4 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    #{{ $product->id }}
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl flex items-center justify-center font-bold shadow-lg group-hover:scale-110 transition-transform">
                                            {{ strtoupper(mb_substr($product->product_name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p
                                                class="font-semibold text-gray-800 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                                {{ $product->product_name }}
                                            </p>
                                            <p class="text-xs text-gray-400 font-mono">كود المنتج: {{ $product->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4">
                                    @php $qty = $product->product_quantity; @endphp
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium
                                        {{ $qty == 0
                                            ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                            : ($qty < 10
                                                ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
                                                : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400') }}">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full {{ $qty == 0 ? 'bg-red-500' : ($qty < 10 ? 'bg-yellow-500' : 'bg-green-500') }} ml-1.5"></span>
                                        {{ number_format($qty) }}
                                    </span>
                                </td>

                                <td class="p-4">
                                    <div class="font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($product->product_price, 2) }}
                                        <span class="text-xs font-normal text-gray-500">ج.م</span>
                                    </div>
                                </td>

                                <td class="p-4">
                                    <span
                                        class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-gray-600 dark:text-gray-300">
                                        {{ $product->category?->category_name ?? 'غير مصنف' }}
                                    </span>
                                </td>

                                <td class="p-4">
                                    <span
                                        class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-gray-600 dark:text-gray-300">
                                        {{ $product->branch?->branch_name ?? 'غير محدد' }}
                                    </span>
                                </td>

                                <td class="p-4">
                                    <div class="text-sm">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            {{ $product->created_at->format('Y/m/d') }}</div>
                                        <div class="text-xs text-gray-400">{{ $product->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </td>

                                <td class="p-4">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:click="edit({{ $product->id }})"
                                            class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200 group-hover:scale-105">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>

                                        <button wire:click="delete({{ $product->id }})"
                                            wire:confirm="هل أنت متأكد من حذف المنتج '{{ $product->product_name }}'؟"
                                            class="p-2 text-red-600 hover:bg-red-100 dark:hover:bg-red-900/30 rounded-lg transition-all duration-200 group-hover:scale-105">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div
                                            class="w-24 h-24 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-lg font-semibold text-gray-600 dark:text-gray-400">لا توجد
                                                منتجات</p>
                                            <p class="text-sm text-gray-400 mt-1">ابدأ بإضافة منتج جديد للنظام</p>
                                        </div>
                                        <flux:modal.trigger name="add-product">
                                            <button
                                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                + إضافة منتج
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
            @if ($this->products->hasPages())
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    {{ $this->products->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
