<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\WithPagination;
use App\Models\Category;

new #[Title('إدارة تصنيفات المنتجات')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public bool $showFilters = false;
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $perPage = '10';

    public function getCategoriesProperty()
    {
        return Category::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('category_name', 'like', "%{$this->search}%")->orWhere('category_description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->paginate($this->perPage);
    }

    public function getTotalCategoriesProperty()
    {
        return Category::count();
    }

    public function getActiveCategoriesCountProperty()
    {
        return Category::where('is_active', true)->count();
    }

    public function getFeaturedCategoriesCountProperty()
    {
        return Category::where('is_featured', true)->count();
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

    public function toggleActive($id)
    {
        abort_unless(auth()->user()?->can('main_categories.update'), 403);

        $category = Category::findOrFail($id);
        $category->update(['is_active' => !$category->is_active]);
        session()->flash('success', 'تم تحديث حالة التصنيف بنجاح');
    }

    public function toggleFeatured($id)
    {
        abort_unless(auth()->user()?->can('main_categories.update'), 403);

        $category = Category::findOrFail($id);
        $category->update(['is_featured' => !$category->is_featured]);
        session()->flash('success', 'تم تحديث حالة المميز بنجاح');
    }

    public function edit($id)
    {
        abort_unless(auth()->user()?->can('main_categories.update'), 403);

        $this->dispatch('editCategory', id: $id);
    }

    public function delete($id)
    {
        abort_unless(auth()->user()?->can('main_categories.delete'), 403);

        Category::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف التصنيف بنجاح');
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->sortBy = 'created_at';
        $this->sortDirection = 'desc';
        $this->resetPage();
    }
};
?>

<div dir="rtl"
    class="min-h-screen bg-gradient-to-br from-slate-50 via-purple-50/20 to-pink-50/20 dark:from-gray-950 dark:via-gray-900 dark:to-slate-950">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Flash Message --}}
        <x-flash-message />

        {{-- Modern Hero Section --}}
        <div
            class="relative overflow-hidden bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800">
            <div
                class="absolute inset-0 bg-gradient-to-r from-purple-600/10 to-pink-600/10 dark:from-purple-600/5 dark:to-pink-600/5">
            </div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-purple-400/10 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-pink-400/10 rounded-full filter blur-3xl"></div>

            <div class="relative px-6 py-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-10 bg-gradient-to-b from-purple-600 to-pink-600 rounded-full"></div>
                            <div>
                                <h1
                                    class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                    إدارة تصنيفات المنتجات
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    منصة متكاملة لإدارة وتنظيم جميع التصنيفات بكفاءة عالية
                                </p>
                            </div>
                        </div>
                    </div>

                    @can('main_categories.create')
                    <flux:modal.trigger name="add-category">
                        <button
                            class="group relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-purple-500/25 hover:shadow-xl hover:shadow-purple-500/30 hover:scale-105 transition-all duration-300 overflow-hidden">
                            <span
                                class="absolute inset-0 bg-gradient-to-r from-purple-500 to-pink-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center gap-2">
                                <svg class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                إضافة تصنيف جديد
                            </span>
                        </button>
                    </flux:modal.trigger>
                    @endcan
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                    <div
                        class="bg-gradient-to-br from-purple-50 to-white dark:from-purple-950/20 dark:to-gray-900 rounded-xl p-4 border border-purple-100 dark:border-purple-900/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-purple-600 dark:text-purple-400">إجمالي التصنيفات</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                    {{ $this->totalCategories }}</p>
                            </div>
                            <div
                                class="w-10 h-10 bg-purple-100 dark:bg-purple-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                </svg>
                            </div>
                        </div>
                    </div>



                    @php
                        $lastUpdated = Category::latest('updated_at')->value('updated_at');
                    @endphp

                    <div
                        class="bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-950/20 dark:to-gray-900 rounded-xl p-4 border border-indigo-100 dark:border-indigo-900/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-indigo-600 dark:text-indigo-400">آخر تحديث</p>

                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1">
                                    {{ $lastUpdated?->translatedFormat('d M Y') }}
                                </p>

                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $lastUpdated?->diffForHumans() }}
                                </p>
                            </div>

                            <div
                                class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modals --}}
        @can('main_categories.create') <livewire:category.create /> @endcan
        @can('main_categories.update') <livewire:category.edit /> @endcan

        {{-- Main Table Card --}}
        <div
            class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300">

            {{-- Enhanced Toolbar --}}
            <div
                class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50/80 to-white dark:from-gray-800/40 dark:to-gray-900">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-gradient-to-b from-purple-500 to-pink-600 rounded-full"></span>
                            قائمة التصنيفات
                        </h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 mr-3">
                            عرض {{ $this->categories->firstItem() ?? 0 }} - {{ $this->categories->lastItem() ?? 0 }}
                            من أصل <span
                                class="font-semibold text-gray-600 dark:text-gray-300">{{ $this->categories->total() }}</span>
                            تصنيف
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">


                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-3 w-4 h-4 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="بحث في التصنيفات..."
                                class="w-full sm:w-80 text-sm border border-gray-200 dark:border-gray-700 rounded-xl pr-9 pl-4 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all" />
                        </div>

                        <select wire:model.live="perPage"
                            class="text-sm border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500/20">
                            <option value="10">10 تصنيفات</option>
                            <option value="25">25 تصنيف</option>
                            <option value="50">50 تصنيف</option>
                            <option value="100">100 تصنيف</option>
                        </select>
                    </div>
                </div>


            </div>

            {{-- Enhanced Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50/80 dark:bg-gray-800/40 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition group"
                                wire:click="sortByColumn('id')">
                                <div class="flex items-center gap-1">
                                    #
                                    @if ($sortBy === 'id')
                                        <svg class="w-3 h-3 transition-transform" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                الصورة
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition group"
                                wire:click="sortByColumn('category_name')">
                                <div class="flex items-center gap-1">
                                    اسم التصنيف
                                    @if ($sortBy === 'category_name')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                الوصف
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition group"
                                wire:click="sortByColumn('is_active')">
                                <div class="flex items-center gap-1">
                                    الحالة
                                    @if ($sortBy === 'is_active')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition group"
                                wire:click="sortByColumn('is_featured')">
                                <div class="flex items-center gap-1">
                                    مميز
                                    @if ($sortBy === 'is_featured')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition group"
                                wire:click="sortByColumn('created_at')">
                                <div class="flex items-center gap-1">
                                    تاريخ الإنشاء
                                    @if ($sortBy === 'created_at')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th
                                class="px-6 py-4 text-end text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                        @forelse ($this->categories as $category)
                            <tr wire:key="category-{{ $category->id }}"
                                class="hover:bg-gradient-to-r hover:from-purple-50/40 hover:to-transparent dark:hover:from-purple-900/10 transition-all duration-300 group">
                                <td class="px-6 py-4 text-gray-400 dark:text-gray-600 text-xs font-mono font-medium">
                                    {{ $loop->iteration + ($this->categories->currentPage() - 1) * $this->categories->perPage() }}
                                </td>

                                {{-- image_path Column --}}
                                <td class="px-6 py-4">
                                    @if ($category->image_path)
                                        <img src="{{ Storage::url($category->image_path) }}"
                                            alt="{{ $category->category_name }}"
                                            class="w-10 h-10 rounded-xl object-cover shadow-sm ring-1 ring-gray-200 dark:ring-gray-700 group-hover:scale-105 transition-transform">
                                    @else
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-100 to-pink-100 dark:from-purple-900/40 dark:to-pink-900/40 flex items-center justify-center text-sm font-bold text-purple-700 dark:text-purple-300 shadow-sm group-hover:scale-105 transition-transform">
                                            {{ mb_substr($category->category_name, 0, 2) }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Name Column --}}
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">
                                            {{ $category->category_name }}</p>
                                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 font-mono">
                                            #{{ $category->id }}</p>
                                    </div>
                                </td>

                                {{-- Description Column --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 6h16M4 12h16M4 18h7" />
                                        </svg>
                                        <span
                                            class="text-sm line-clamp-1 max-w-[250px]">{{ $category->category_description ?? 'لا يوجد وصف' }}</span>
                                    </div>
                                </td>

                                {{-- Active Toggle --}}
                                <td class="px-6 py-4">
                                    @can('main_categories.update')
                                    <button wire:click="toggleActive({{ $category->id }})"
                                        class="focus:outline-none">
                                        @if ($category->is_active)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                نشط
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                غير نشط
                                            </span>
                                        @endif
                                    </button>
                                    @endcan
                                </td>

                                {{-- Featured Toggle --}}
                                <td class="px-6 py-4">
                                    @can('main_categories.update')
                                    <button wire:click="toggleFeatured({{ $category->id }})"
                                        class="focus:outline-none">
                                        @if ($category->is_featured)
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                                مميز
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                                غير مميز
                                            </span>
                                        @endif
                                    </button>
                                    @endcan
                                </td>

                                {{-- Created At --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-0.5">
                                        <span
                                            class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $category->created_at->translatedFormat('d F Y') }}</span>
                                        <span
                                            class="text-xs text-gray-400 dark:text-gray-500">{{ $category->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>

                                {{-- Actions --}}
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        @can('main_categories.update')
                                        <button wire:click="edit({{ $category->id }})"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-purple-900/20 hover:border-purple-200 dark:hover:border-purple-800 hover:text-purple-700 transition-all duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                            </svg>
                                            تعديل
                                        </button>
                                        @endcan
                                        @can('main_categories.delete')
                                        <button wire:click="delete({{ $category->id }})"
                                            wire:confirm="هل أنت متأكد من حذف هذا التصنيف؟"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium rounded-lg border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-200 transition-all duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            حذف
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div
                                            class="w-24 h-24 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">لا توجد
                                                تصنيفات</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">ابدأ بإضافة تصنيف
                                                جديد من الزر أعلاه</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Enhanced Pagination --}}
            @if ($this->categories->hasPages())
                <div
                    class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50/30 to-transparent dark:from-gray-800/20">
                    {{ $this->categories->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
