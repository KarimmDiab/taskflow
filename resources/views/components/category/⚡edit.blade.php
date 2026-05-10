<?php

use Livewire\Component;
use App\Livewire\Forms\CategoryForm;
use Livewire\Attributes\On;
use App\Models\Category;

new class extends Component
{
    public CategoryForm $form;
    public bool $isUpdating = false;

    #[On('editCategory')]
    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        $this->form->setCategory($category);
        Flux::modal('edit-category')->show();
    }

    public function updateCategory()
    {
        $this->isUpdating = true;

        try {
            $this->form->update();
            Flux::modal('edit-category')->close();
            session()->flash('warning', 'تم تحديث بيانات التصنيف بنجاح');
            $this->redirectRoute('categories', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء تحديث التصنيف');
            $this->isUpdating = false;
        }
    }

    public function confirmDelete($id)
    {
        $category = Category::findOrFail($id);
        $this->form->setCategory($category);
        Flux::modal('delete-category')->show();
    }

    public function deleteCategory()
    {
        $this->form->category->delete();
        Flux::modal('delete-category')->close();
        session()->flash('success', 'تم حذف بيانات التصنيف بنجاح');
        $this->redirectRoute('categories', navigate: true);
    }
};
?>

<div>

<flux:modal name="edit-category" class="md:w-[580px] overflow-hidden rounded-2xl" style="padding: 0;">

    <form wire:submit.prevent="updateCategory" class="relative">

        {{-- Background overlay --}}
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/50 via-orange-50/30 to-yellow-50/50 dark:from-amber-950/10 dark:via-orange-950/5 dark:to-yellow-950/10 pointer-events-none"></div>

        {{-- Header --}}
        <div class="relative px-6 pt-6 pb-4" style="z-index: 1;">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300">
                            تعديل
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            تصنيف
                        </span>
                    </div>

                    <flux:heading size="lg" class="font-bold text-slate-800 dark:text-white">
                        تعديل التصنيف
                    </flux:heading>

                    <flux:text class="mt-2 text-slate-500 dark:text-slate-400">
                        قم بتعديل تفاصيل التصنيف الحالي
                    </flux:text>
                </div>

                {{-- Decorative icon --}}
                <div class="hidden sm:block">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="relative px-6 space-y-5" style="z-index: 1;">

            <!-- Category Name -->
            <div class="group">
                <flux:label style="z-index: 1;" class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                    اسم التصنيف
                    <span class="text-red-500">*</span>
                </flux:label>
                <div class="relative">
                    <flux:input
                        placeholder="مثال: منتجات إلكترونية"
                        wire:model="form.category_name"
                        wire:dirty.class="border-amber-400 ring-4 ring-amber-400/20"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-amber-400 focus:ring-4 focus:ring-amber-400/20 transition-all duration-200"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400 group-focus-within:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                    </div>
                </div>
                @error('form.category_name')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Category Description -->
            <div class="group">
                <flux:label class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    وصف التصنيف
                    <span class="text-slate-400 text-xs font-normal">(اختياري)</span>
                </flux:label>
                <flux:textarea
                    placeholder="أدخل وصفاً موجزاً للتصنيف..."
                    wire:model="form.category_description"
                    wire:dirty.class="border-amber-400 ring-4 ring-amber-400/20"
                    rows="4"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-orange-400 focus:ring-4 focus:ring-orange-400/20 transition-all duration-200 resize-none"
                />
                @error('form.category_description')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dirty Indicator & Character counter -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs" wire:dirty>
                    <div class="flex items-center gap-1 text-amber-600 dark:text-amber-400">
                        <svg class="w-3.5 h-3.5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span>تم تعديل البيانات</span>
                    </div>
                </div>
                <div>
                    <span class="text-xs text-slate-400" x-data="{ text: $wire.entangle('form.category_description') }" x-text="`${(text || '').length} / 200 حرف`"></span>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="relative px-6 py-5 mt-8 border-t border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-end gap-3">

                <flux:modal.close>
                    <flux:button
                        type="button"
                        class="group px-5 py-2.5 rounded-xl font-medium text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 transition-all duration-200"
                        :disabled="$isUpdating"
                    >
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            الغاء
                        </div>
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit" style="z-index: 1;"
                    class="px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
                    :disabled="$isUpdating"
                >
                    <div class="flex items-center gap-2">
                        @if($isUpdating)
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري التحديث...
                        @else
                            <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            تحديث التصنيف
                        @endif
                    </div>
                </flux:button>

            </div>
        </div>

        {{-- Decorative elements --}}
        <div style="z-index: 0;" class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-amber-100/40 to-orange-100/40 dark:from-amber-900/10 dark:to-orange-900/10 rounded-tr-full pointer-events-none"></div>
        <div style="z-index: 0;" class="absolute top-20 right-0 w-20 h-20 bg-gradient-to-bl from-yellow-100/40 to-amber-100/40 dark:from-yellow-900/5 dark:to-amber-900/5 rounded-bl-full pointer-events-none"></div>

    </form>

</flux:modal>

<style>
    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translateY(30px) scale(0.96);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    @keyframes dirtyPulse {
        0%, 100% {
            border-color: rgba(245, 158, 11, 0.3);
            box-shadow: 0 0 0 0 rgba(245, 158, 11, 0.1);
        }
        50% {
            border-color: rgba(245, 158, 11, 0.6);
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.2);
        }
    }

    [flux\:modal="edit-category"] {
        animation: modalSlideUp 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
    }

    input:focus, textarea:focus {
        outline: none;
    }

    button, input, textarea {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    textarea::-webkit-scrollbar {
        width: 6px;
    }

    textarea::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    textarea::-webkit-scrollbar-thumb {
        background: #f59e0b;
        border-radius: 10px;
    }

    textarea::-webkit-scrollbar-thumb:hover {
        background: #d97706;
    }

    /* Dirty field highlight animation */
    [wire\:dirty] input, [wire\:dirty] textarea {
        animation: dirtyPulse 1.5s ease-in-out 2;
    }
</style>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</div>
