<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;
use App\Models\Branches;

new class extends Component {
    use WithPagination;

    public string $search = '';
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

public function getBranchesProperty()
{
    return Branches::query()
        ->orderBy($this->sortBy, $this->sortDirection)
        ->when($this->search, function ($q) {
            $q->where(function ($query) {
                $query->where('branch_name', 'like', "%{$this->search}%")
                      ->orWhere('branch_address', 'like', "%{$this->search}%");
            });
        })
        ->paginate(10);
}

public function getTotalBranchesProperty()
{
    return Branches::count();
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
        $this->dispatch('editBranch', id: $id);
    }

    public function delete($id)
    {
        Branches::findOrFail($id)->delete();
        session()->flash('success', 'تم حذف الفرع بنجاح');
        $this->resetPage();
    }
};
?>

<div dir="rtl"
    class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/30 dark:from-gray-950 dark:via-gray-900 dark:to-slate-950">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        {{-- Flash Message --}}
        <x-flash-message />

        {{-- Page Header with Gradient Accent --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-8 bg-gradient-to-b from-blue-600 to-indigo-600 rounded-full"></div>
                    <h1
                        class="text-2xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 bg-clip-text text-transparent">
                        إدارة الفروع
                    </h1>
                    <span
                        class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                        {{ $this->totalBranches }} إجمالي
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 pr-5">
                    إدارة وتنظيم جميع الفروع بكفاءة عالية وإحصائيات دقيقة
                </p>
            </div>

            <flux:modal.trigger name="add-branch">
                <button
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-medium rounded-xl shadow-lg shadow-blue-500/20 hover:shadow-xl hover:shadow-blue-500/30 hover:scale-105 transition-all duration-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    إضافة فرع جديد
                </button>
            </flux:modal.trigger>
        </div>


        {{-- Modals --}}
        <livewire:branch.create />
        <livewire:branch.edit />

        {{-- Enhanced Table Card --}}
        <div
            class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800 overflow-hidden transition-all duration-300">

            {{-- Toolbar with Gradient Border --}}
            <div
                class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50/50 to-white dark:from-gray-800/30 dark:to-gray-900">
                <div class="flex flex-col sm:flex-row sm:items-center gap-4 justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-800 dark:text-white flex items-center gap-2">
                            <span class="w-1.5 h-5 bg-gradient-to-b from-blue-500 to-indigo-600 rounded-full"></span>
                            قائمة الفروع
                        </h3>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1.5 mr-2.5">
                            عرض {{ $this->branches->firstItem() ?? 0 }} - {{ $this->branches->lastItem() ?? 0 }}
                            من أصل <span
                                class="font-medium text-gray-600 dark:text-gray-300">{{ $this->branches->total() }}</span>
                            فرع
                        </p>
                    </div>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="absolute top-1/2 -translate-y-1/2 right-3 w-4 h-4 text-gray-400 pointer-events-none"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="بحث في الفروع..."
                            class="w-full sm:w-72 text-sm border border-gray-200 dark:border-gray-700 rounded-xl pr-9 pl-4 py-2.5 bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all duration-200" />
                    </div>
                </div>
            </div>

            {{-- Table with enhanced styling --}}
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-gray-800/40 border-b border-gray-100 dark:border-gray-800">
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300 transition"
                                wire:click="sortByColumn('id')">
                                <div class="flex items-center gap-1">
                                    #
                                    @if ($sortBy === 'id')
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}">
                                            </path>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300 transition"
                                wire:click="sortByColumn('branch_name')">
                                <div class="flex items-center gap-1">
                                    اسم الفرع
                                    @if ($sortBy === 'branch_name')
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
                                class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                العنوان</th>
                            <th class="px-6 py-4 text-start text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:text-gray-700 dark:hover:text-gray-300 transition"
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
                                الإجراءات</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/50">
                        @forelse ($this->branches as $branch)
                            <tr wire:key="branch-{{ $branch->id }}"
                                class="hover:bg-gradient-to-r hover:from-blue-50/30 hover:to-transparent dark:hover:from-blue-900/10 dark:hover:to-transparent transition-all duration-200 group">
                                <td class="px-6 py-4 text-gray-400 dark:text-gray-600 text-xs font-mono font-medium">
                                    {{ str_pad($branch->id, 3, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center text-sm font-bold text-blue-700 dark:text-blue-300 shadow-sm">
                                            {{ mb_substr($branch->branch_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white text-sm">
                                                {{ $branch->branch_name }}
                                            </p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-0.5 font-mono">
                                                ID: {{ $branch->id }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-3.5 h-3.5 shrink-0 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                        </svg>
                                        <span
                                            class="text-sm truncate max-w-[220px]">{{ $branch->branch_address }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ $branch->created_at->format('d M Y') }}
                                        </span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">
                                            {{ $branch->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-2">
                                        <button wire:click="edit({{ $branch->id }})"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium rounded-lg border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-200 dark:hover:border-blue-800 hover:text-blue-700 dark:hover:text-blue-300 transition-all duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                            </svg>
                                            تعديل
                                        </button>

                                        <button wire:click="delete({{ $branch->id }})"
                                            wire:confirm="هل أنت متأكد من حذف هذا الفرع؟"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 text-xs font-medium rounded-lg border border-red-100 dark:border-red-900/30 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-200 dark:hover:border-red-800 transition-all duration-150">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            حذف
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-20 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div
                                            class="w-20 h-20 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <p class="text-base font-semibold text-gray-700 dark:text-gray-300">لا توجد
                                                فروع</p>
                                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">ابدأ بإضافة فرع
                                                جديد من الزر أعلاه</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Enhanced Pagination Footer --}}
            @if ($this->branches->hasPages())
                <div
                    class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 bg-gradient-to-r from-gray-50/30 to-transparent dark:from-gray-800/20 dark:to-transparent">
                    {{ $this->branches->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
