<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Customer;
use Livewire\Attributes\Title;

new #[Title('إدارة العملاء')] class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';
    public bool $showFilters = false;
    public string $dateFrom = '';
    public string $dateTo = '';
    public string $perPage = '10';

    public function getCustomersProperty()
    {
        return Customer::query()
            ->orderBy($this->sortBy, $this->sortDirection)
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->where('customer_name', 'like', "%{$this->search}%")
                          ->orWhere('contact_info', 'like', "%{$this->search}%");
                });
            })
            ->when($this->dateFrom, fn($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->paginate($this->perPage);
    }

    public function getTotalCustomersProperty()
    {
        return Customer::count();
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

    public function edit($id)
    {
        $this->dispatch('editCustomer', id: $id);
    }

    public function delete($id)
    {
        Customer::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف بيانات العميل بنجاح');
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
    class="min-h-screen bg-gradient-to-br from-slate-50 via-emerald-50/20 to-teal-50/20 dark:from-gray-950 dark:via-gray-900 dark:to-slate-950">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

        {{-- Flash Message --}}
        <x-flash-message />

        {{-- Modern Hero Section --}}
        <div class="relative overflow-hidden bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/10 to-teal-600/10 dark:from-emerald-600/5 dark:to-teal-600/5"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/10 rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-teal-400/10 rounded-full filter blur-3xl"></div>

            <div class="relative px-6 py-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    <div class="space-y-2">
                        <div class="flex items-center gap-3">
                            <div class="w-1.5 h-10 bg-gradient-to-b from-emerald-600 to-teal-600 rounded-full"></div>
                            <div>
                                <h1 class="text-3xl font-bold bg-gradient-to-r from-gray-900 via-gray-800 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                                    إدارة العملاء
                                </h1>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    منصة متكاملة لإدارة وتنظيم جميع العملاء بكفاءة عالية
                                </p>
                            </div>
                        </div>
                    </div>

                    <flux:modal.trigger name="add-customer">
                        <button class="group relative inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-xl hover:shadow-emerald-500/30 hover:scale-105 transition-all duration-300 overflow-hidden">
                            <span class="absolute inset-0 bg-gradient-to-r from-emerald-500 to-teal-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
                            <span class="relative flex items-center gap-2">
                                <svg class="w-4 h-4 group-hover:rotate-12 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                إضافة عميل جديد
                            </span>
                        </button>
                    </flux:modal.trigger>
                </div>

                {{-- Stats Cards (Fixed - 3 cards now) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-8">
                    <!-- Total Customers -->
                    <div class="bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-950/20 dark:to-gray-900 rounded-xl p-4 border border-emerald-100 dark:border-emerald-900/30 hover:shadow-lg transition-all duration-300 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-emerald-600 dark:text-emerald-400 font-medium">إجمالي العملاء</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $this->totalCustomers }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">منذ بداية التسجيل</p>
                            </div>
                            <div class="w-12 h-12 bg-emerald-100 dark:bg-emerald-900/50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>



                    <!-- Last Update -->
                    <div class="bg-gradient-to-br from-cyan-50 to-white dark:from-cyan-950/20 dark:to-gray-900 rounded-xl p-4 border border-cyan-100 dark:border-cyan-900/30 hover:shadow-lg transition-all duration-300 group">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-cyan-600 dark:text-cyan-400 font-medium">آخر تحديث</p>
                                <p class="text-xl font-bold text-gray-900 dark:text-white mt-1">{{ now()->translatedFormat('d F Y') }}</p>
                                <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">أحدث الإضافات</p>
                            </div>
                            <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/50 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-6 h-6 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modals --}}
        <livewire:customer.create />
        <livewire:customer.edit />

        {{-- Main Table Card --}}
        <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300">

            {{-- Enhanced Toolbar --}}
            <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50/80 to-white dark:from-gray-800/40 dark:to-gray-900">
                <div class="flex flex-col lg:flex-row lg:items-center gap-4 justify-between">
                    <div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-6 bg-gradient-to-b from-emerald-500 to-teal-600 rounded-full"></span>
                            قائمة العملاء
                        </h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 mr-3">
                            عرض {{ $this->customers->firstItem() ?? 0 }} - {{ $this->customers->lastItem() ?? 0 }}
                            من أصل <span class="font-semibold text-gray-600 dark:text-gray-300">{{ $this->customers->total() }}</span> عميل
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">


                        <div class="relative">
                            <svg class="absolute top-1/2 -translate-y-1/2 right-3 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                            </svg>
                            <input type="text" wire:model.live.debounce.300ms="search"
                                placeholder="بحث بالاسم أو الجوال..."
                                class="w-full sm:w-80 text-sm border border-gray-200 dark:border-gray-700 rounded-xl pr-9 pl-4 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200" />
                        </div>

                        <select wire:model.live="perPage"
                            class="text-sm border border-gray-200 dark:border-gray-700 rounded-xl px-3 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all duration-200 cursor-pointer">
                            <option value="10">10 عملاء</option>
                            <option value="25">25 عميل</option>
                            <option value="50">50 عميل</option>
                            <option value="100">100 عميل</option>
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
                                        <svg class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-start text-xs  text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition group"
                                wire:click="sortByColumn('customer_name')">
                                <div class="flex items-center gap-1">
                                    اسم العميل
                                    @if ($sortBy === 'customer_name')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 transition group"
                                wire:click="sortByColumn('contact_info')">
                                <div class="flex items-center gap-1">
                                    رقم الموبايل
                                    @if ($sortBy === 'contact_info')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    تاريخ التسجيل
                                    @if ($sortBy === 'created_at')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-end text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                الإجراءات
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                        @forelse ($this->customers as $customer)
                            <tr wire:key="customer-{{ $customer->id }}"
                                class="hover:bg-gradient-to-r hover:from-emerald-50/40 hover:to-transparent dark:hover:from-emerald-900/10 transition-all duration-300 group">
                                <td class="px-6 py-4 text-gray-400 dark:text-gray-600 text-xs ">
                                    {{ $loop->iteration + ($this->customers->currentPage() - 1) * $this->customers->perPage() }}


                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/40 dark:to-teal-900/40 flex items-center justify-center text-base font-bold text-emerald-700 dark:text-emerald-300 shadow-sm group-hover:scale-110 transition-all duration-300">
                                            {{ mb_substr($customer->customer_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class=" text-gray-900 dark:text-white">
                                                {{ $customer->customer_name }}
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 ">
                                                رقم العميل: {{ $customer->id }}
                                            </p>
                                        </div>
                                    </div>


                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span class="text-sm font-mono tracking-wide">{{ $customer->contact_info }}</span>
                                    </div>


                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $customer->created_at->translatedFormat('d F Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $customer->created_at->diffForHumans() }}
                                        </span>
                                    </div>


                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        <button wire:click="edit({{ $customer->id }})"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-800 hover:text-emerald-700 transition-all duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                            </svg>
                                            تعديل
                                        </button>
                                        <button wire:click="delete({{ $customer->id }})"
                                            wire:confirm="هل أنت متأكد من حذف بيانات العميل؟"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium rounded-lg border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-200 transition-all duration-150">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            حذف
                                        </button>
                                    </div>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center animate-pulse">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-lg font-semibold text-gray-700 dark:text-gray-300">لا يوجد عملاء</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">ابدأ بإضافة عميل جديد من الزر أعلاه</p>
                                        </div>
                                    </div>
                                \n
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Enhanced Pagination --}}
            @if ($this->customers->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50/30 to-transparent dark:from-gray-800/20">
                    {{ $this->customers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
