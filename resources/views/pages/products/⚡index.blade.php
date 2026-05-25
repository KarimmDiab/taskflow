<?php

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\Branches;

new #[Title('إدارة المنتجات')] class extends Component {
    use WithPagination;

    // Search & Filter properties
    public string $search = '';
    public string $filterCategory = '';
    public string $filterBranch = '';
    public string $filterStatus = 'Active';
    public string $filterStockStatus = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public int $perPage = 10;

    // Bulk selection
    public array $selectedProducts = [];
    public bool $selectAll = false;

    public function getProductsProperty()
    {
        return Product::with(['category', 'subCategory', 'branch', 'primaryImage', 'productVariants.inventories.branch'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query
                        ->where('product_name', 'like', "%{$this->search}%")
                        ->orWhereHas('category', function ($q) {
                            $q->where('category_name', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('productVariants.inventories.branch', function ($q) {
                            $q->where('branch_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->filterCategory, function ($q) {
                $q->where('category_id', $this->filterCategory);
            })
            ->when($this->filterBranch, function ($q) {
                $q->whereHas('productVariants.inventories', function ($q) {
                    $q->where('branch_id', $this->filterBranch);
                });
            })
            ->when($this->filterStockStatus === 'in_stock', function ($q) {
                $q->where('product_quantity', '>', 10);
            })
            ->when($this->filterStockStatus === 'low_stock', function ($q) {
                $q->whereBetween('product_quantity', [1, 9]);
            })
            ->when($this->filterStockStatus === 'out_of_stock', function ($q) {
                $q->where('product_quantity', 0);
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate($this->perPage);
    }

    public function getTotalProductsProperty()
    {
        return Product::count();
    }

    public function getActiveProductsProperty()
    {
        return Product::where('product_quantity', '>', 0)->count();
    }

    public function getLowStockCountProperty()
    {
        return Product::whereBetween('product_quantity', [1, 9])->count();
    }

    public function getOutOfStockCountProperty()
    {
        return Product::where('product_quantity', 0)->count();
    }

    public function getInventoryValueProperty()
    {
        return Product::all()->sum(function ($product) {
            $avgPrice = ($product->product_cost + $product->product_price) / 2;
            return $avgPrice * $product->product_quantity;
        });
    }

    public function getCategoriesProperty()
    {
        return Category::all();
    }

    public function getBranchesProperty()
    {
        return Branches::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updated($name)
    {
        if (in_array($name, ['filterCategory', 'filterBranch', 'filterStatus', 'filterStockStatus', 'sortBy', 'sortDirection'])) {
            $this->resetPage();
        }
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
            return '↕';
        }
        return $this->sortDirection === 'asc' ? '↑' : '↓';
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->filterCategory = '';
        $this->filterBranch = '';
        $this->filterStockStatus = '';
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }

    #[On('editProduct')]
    public function editProduct($id)
    {
        $this->dispatch('openEditModal', id: $id);
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $productName = $product->product_name;
        $product->delete();
        session()->flash('success', "تم حذف المنتج '{$productName}' بنجاح");
        $this->resetPage();
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            $this->selectedProducts = $this->products->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selectedProducts = [];
        }
    }

    public function clearSelection()
    {
        $this->selectedProducts = [];
        $this->selectAll = false;
    }

    public function bulkDelete()
    {
        if (empty($this->selectedProducts)) {
            return;
        }
        Product::whereIn('id', $this->selectedProducts)->delete();
        session()->flash('success', 'تم حذف ' . count($this->selectedProducts) . ' منتج بنجاح');
        $this->clearSelection();
        $this->resetPage();
    }
};
?>



<div dir="rtl" class="min-h-screen bg-gray-50 dark:bg-gray-950 py-8 px-4 sm:px-6 lg:px-8">
    <x-flash-message />

    <div class="max-w-[1600px] mx-auto">
        <!-- HEADER with Add Button -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8" dir="ltr">
            <div>
                <a href="{{ route('products.create') }}" wire:navigate>
                    <button
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-sm font-semibold rounded-lg shadow-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span>إضافة منتج جديد</span>
                    </button>
                </a>
            </div>
            <div class="text-right">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">إدارة المنتجات</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">إدارة وعرض وتعديل جميع المنتجات في نظامك</p>
            </div>
        </div>

        <!-- STATS CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">
            <!-- Total Products -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-r-4 border-blue-500 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">إجمالي المنتجات</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->totalProducts }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Products -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-r-4 border-emerald-500 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">منتجات متاحة</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->activeProducts }}</p>
                    </div>
                    <div class="bg-emerald-100 dark:bg-emerald-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-r-4 border-amber-500 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">منخفضة المخزون</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500">أقل من 10 قطع</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->lowStockCount }}</p>
                    </div>
                    <div class="bg-amber-100 dark:bg-amber-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Out of Stock -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-r-4 border-rose-500 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">نفذت الكمية</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->outOfStockCount }}
                        </p>
                    </div>
                    <div class="bg-rose-100 dark:bg-rose-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Inventory Value -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border-r-4 border-sky-500 p-5 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 dark:text-gray-400 text-sm font-medium">قيمة المخزون</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ number_format($this->inventoryValue, 0) }} ج.م</p>
                    </div>
                    <div class="bg-sky-100 dark:bg-sky-900/30 rounded-xl p-3">
                        <svg class="w-6 h-6 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- FILTERS TOOLBAR (sticky) -->
        <div
            class="sticky top-0 z-20 bg-white dark:bg-gray-800 backdrop-blur-sm py-4 mb-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <div class="px-6 space-y-4">
                <div class="flex flex-wrap items-end gap-3">
                    <!-- Search -->
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">البحث</label>
                        <div class="relative">
                            <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="ابحث عن منتج أو كود..."
                                class="w-full pr-10 pl-4 py-2 border border-gray-200 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all">
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div class="w-40">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">التصنيف</label>
                        <select wire:model.live="filterCategory"
                            class="w-full border border-gray-200 dark:border-gray-600 rounded-lg py-2 px-3 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">جميع التصنيفات</option>
                            @foreach ($this->categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Branch Filter -->
                    <div class="w-40">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">الفرع</label>
                        <select wire:model.live="filterBranch"
                            class="w-full border border-gray-200 dark:border-gray-600 rounded-lg py-2 px-3 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">جميع الفروع</option>
                            @foreach ($this->branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->branch_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Stock Status Filter -->
                    <div class="w-40">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">حالة
                            المخزون</label>
                        <select wire:model.live="filterStockStatus"
                            class="w-full border border-gray-200 dark:border-gray-600 rounded-lg py-2 px-3 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="">الكل</option>
                            <option value="in_stock">متوفر</option>
                            <option value="low_stock">منخفض</option>
                            <option value="out_of_stock">نفد</option>
                        </select>
                    </div>

                    <!-- Per Page -->
                    <div class="w-32">
                        <label class="block text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1">العدد</label>
                        <select wire:model.live="perPage"
                            class="w-full border border-gray-200 dark:border-gray-600 rounded-lg py-2 px-3 dark:bg-gray-700 dark:text-white text-sm">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>

                    <!-- Reset Button -->
                    <button wire:click="resetFilters()"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition shadow-sm">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        إعادة تعيين
                    </button>
                </div>
            </div>
        </div>

        <!-- Bulk Actions Bar -->
        @if (count($selectedProducts) > 0)
            <div
                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg px-4 py-3 mb-4 flex items-center justify-between">
                <div class="text-sm text-blue-800 dark:text-blue-300">
                    <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span>{{ count($selectedProducts) }} منتج(ات) محددة</span>
                </div>
                <div class="flex gap-2">
                    <button wire:click="bulkDelete()" wire:confirm="هل تريد حذف {{ count($selectedProducts) }} منتج؟"
                        class="px-3 py-1.5 bg-white dark:bg-gray-800 border border-red-300 dark:border-red-700 text-red-600 dark:text-red-400 rounded-md text-xs font-medium hover:bg-red-50 dark:hover:bg-red-900/20">
                        حذف
                    </button>
                    <button wire:click="clearSelection()"
                        class="px-3 py-1.5 text-gray-500 text-xs hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        إلغاء
                    </button>
                </div>
            </div>
        @endif

        <!-- PRODUCTS TABLE -->
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <!-- Loading Overlay -->
            <div wire:loading.flex
                class="absolute inset-0 bg-black/20 backdrop-blur-sm items-center justify-center z-50 rounded-xl">
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-2xl flex flex-col items-center gap-4">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent">
                    </div>
                    <p class="text-gray-600 dark:text-gray-300">جاري التحميل...</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th class="w-10 px-4 py-3">
                                <input type="checkbox" wire:click="toggleSelectAll()" wire:model.live="selectAll"
                                    class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400">
                            </th>
                            <th class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                                wire:click="sortByColumn('id')">
                                <div class="flex items-center justify-between gap-2">
                                    # <span>{{ $this->getSortIcon('id') }}</span>
                                </div>
                            </th>
                            <th class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                                wire:click="sortByColumn('product_name')">
                                <div class="flex items-center justify-between gap-2">
                                    المنتج <span>{{ $this->getSortIcon('product_name') }}</span>
                                </div>
                            </th>
                            <th
                                class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3">
                                المتغيرات</th>
                            <th
                                class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3">
                                الكمية</th>
                            <th
                                class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3">
                                سعر التكلفة</th>
                            <th
                                class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3">
                                سعر البيع</th>
                            <th
                                class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3">
                                الحالة</th>
                            <th
                                class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3">
                                الفروع</th>
                            <th class="text-right text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3 cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                                wire:click="sortByColumn('created_at')">
                                <div class="flex items-center justify-between gap-2">
                                    تاريخ الإضافة <span>{{ $this->getSortIcon('created_at') }}</span>
                                </div>
                            </th>
                            <th
                                class="text-center text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider px-4 py-3">
                                الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse ($this->products as $product)
                            <tr class="hover:bg-blue-50 dark:hover:bg-blue-900/10 transition-all duration-200 group"
                                :class="{ 'bg-gray-50 dark:bg-gray-800/50': $wire.selectedProducts.includes(
                                        {{ $product->id }}) }">
                                <td class="px-4 py-3" @click.stop>
                                    <input type="checkbox" wire:model.live="selectedProducts"
                                        value="{{ $product->id }}"
                                        class="rounded border-gray-300 dark:border-gray-600 text-blue-600 dark:text-blue-500 focus:ring-blue-500 dark:focus:ring-blue-400">
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-500 dark:text-gray-400 font-mono">
                                    #{{ $product->id }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">


                                        @if ($product->primaryImage?->image_path)
                                            <img src="{{ Storage::url($product->primaryImage->image_path) }}"
                                                alt="{{ $product->product_name }}"
                                                class="w-12 h-12 rounded-xl object-cover shadow-lg" />
                                        @else
                                            <div
                                                class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl flex items-center justify-center font-bold shadow-lg group-hover:scale-110 transition-transform">
                                                {{ strtoupper(mb_substr($product->product_name, 0, 1)) }}
                                            </div>
                                        @endif
                                        <div>
                                            <p
                                                class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                                {{ $product->product_name }}
                                            </p>
                                            <p class="text-xs text-gray-400 font-mono">
                                                {{ $product->product_code ?? $product->id }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $product->category?->category_name ?? 'غير مصنف' }} @if ($product->subCategory)
                                                    / {{ $product->subCategory->sub_category_name }}
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm text-gray-900 dark:text-white font-semibold">
                                        {{ $product->productVariants->count() }} متغير</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $product->productVariants->pluck('color_id')->filter()->unique()->count() }}
                                        ألوان ·
                                        {{ $product->productVariants->pluck('size_id')->filter()->unique()->count() }}
                                        مقاسات
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $qty = $product->product_quantity;
                                        $statusLabel = $qty == 0 ? 'نفد' : ($qty < 10 ? 'منخفض' : 'متوفر');
                                        $statusClass =
                                            $qty == 0
                                                ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400'
                                                : ($qty < 10
                                                    ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400'
                                                    : 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400');
                                    @endphp
                                    <div class="font-semibold text-gray-900 dark:text-white">{{ number_format($qty) }}
                                        قطعة</div>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium {{ $statusClass }} mt-1">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($product->product_cost, 2) }}
                                        <span class="text-xs font-normal text-gray-500 dark:text-gray-400">ج.م</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-green-600 dark:text-green-400">
                                        {{ number_format($product->product_price, 2) }}
                                        <span class="text-xs font-normal text-gray-500 dark:text-gray-400">ج.م</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $status = $product->is_active > 0 ? 'active' : 'inactive';
                                        $statusLabel = $status === 'active' ? 'نشط' : 'غير نشط';
                                        $statusBadgeClass =
                                            $status === 'active'
                                                ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400'
                                                : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
                                    @endphp
                                    <span
                                        class="px-3 py-1.5 rounded-lg text-xs font-semibold {{ $statusBadgeClass }} inline-flex items-center gap-2">
                                        <span
                                            class="w-2 h-2 rounded-full {{ $status === 'active' ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse ($product->productVariants->pluck('inventory')->flatten()->where('quantity', '>', 0) as $inventory)
                                            <span
                                                class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs text-gray-600 dark:text-gray-300">
                                                {{ $inventory->branch?->branch_name }}
                                            </span>
                                        @empty
                                            <span
                                                class="px-2 py-1 bg-red-100 dark:bg-red-900/30 rounded-lg text-xs text-red-600 dark:text-red-400">
                                                غير موزع
                                            </span>
                                        @endforelse
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-sm">
                                        <div class="text-gray-700 dark:text-gray-300">
                                            {{ $product->created_at->format('Y/m/d') }}</div>
                                        <div class="text-xs text-gray-400">{{ $product->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button wire:navigate href="{{ route('products.edit', $product->id) }}"
                                            class="p-2 text-blue-600 hover:bg-blue-100 dark:hover:bg-blue-900/30 rounded-lg transition-all duration-200 group-hover:scale-105">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </button>
                                        <button wire:click="deleteProduct({{ $product->id }})"
                                            wire:confirm="هل أنت متأكد من حذف '{{ $product->product_name }}'؟"
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
                                <td colspan="10" class="p-12 text-center">
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
                                        <a href="{{ route('products.create') }}" wire:navigate>
                                            <button
                                                class="mt-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                                + إضافة منتج
                                            </button>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($this->products->hasPages())
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <div class="flex items-center justify-between flex-wrap gap-3">
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            عرض <span
                                class="font-semibold">{{ ($this->products->currentPage() - 1) * $this->perPage + 1 }}</span>
                            إلى
                            <span
                                class="font-semibold">{{ min($this->products->currentPage() * $this->perPage, $this->products->total()) }}</span>
                            من
                            <span class="font-semibold">{{ $this->products->total() }}</span> منتج
                        </div>
                        <div class="flex gap-2">
                            {{ $this->products->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

  
</div>
