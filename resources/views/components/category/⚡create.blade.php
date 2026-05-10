<?php

use Livewire\Component;
use App\Livewire\Forms\CategoryForm;

new class extends Component {
    public CategoryForm $form;
    public bool $isSaving = false;

    public function save()
    {
        $this->isSaving = true;

        try {
            $this->form->store();
            Flux::modal('add-category')->close();
            session()->flash('success', 'تم اضافة التصنيف بنجاح');
            $this->redirectRoute('categories', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء اضافة التصنيف');
            $this->isSaving = false;
        }
    }
};
?>

<div>

<flux:modal name="add-category" class="md:w-[580px] overflow-hidden rounded-2xl" style="padding: 0;">

    <form wire:submit.prevent="save" class="relative">

        {{-- Background overlay --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50/50 via-indigo-50/30 to-slate-50/50 dark:from-blue-950/10 dark:via-indigo-950/5 dark:to-slate-950/10 pointer-events-none"></div>

        {{-- Header --}}
        <div class="relative px-6 pt-6 pb-4" style="z-index: 1;">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-300">
                            جديد
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                            تصنيف
                        </span>
                    </div>

                    <flux:heading size="lg" class="font-bold text-slate-800 dark:text-white">
                        اضافة تصنيف جديد
                    </flux:heading>

                    <flux:text class="mt-2 text-slate-500 dark:text-slate-400">
                        قم بإضافة تصنيف جديد لتنظيم المحتوى بشكل أفضل
                    </flux:text>
                </div>

                {{-- Decorative icon --}}
                <div class="hidden sm:block">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-blue-900/30 dark:to-indigo-900/30 flex items-center justify-center shadow-sm">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 01.586 1.414V19a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="relative px-6 space-y-5" style="z-index: 1;">

            <!-- Category Name -->
            <div class="group" >
                <flux:label class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                    </svg>
                    اسم التصنيف
                    <span class="text-red-500">*</span>
                </flux:label>
                <div class="relative">
                    <flux:input
                        placeholder="مثال: منتجات إلكترونية"
                        wire:model="form.category_name"
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-blue-400 focus:ring-4 focus:ring-blue-400/20 transition-all duration-200"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
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
                    <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                    </svg>
                    وصف التصنيف
                    <span class="text-slate-400 text-xs font-normal">(اختياري)</span>
                </flux:label>
                <flux:textarea
                    placeholder="أدخل وصفاً موجزاً للتصنيف..."
                    wire:model="form.category_description"
                    rows="4"
                    class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-indigo-400 focus:ring-4 focus:ring-indigo-400/20 transition-all duration-200 resize-none"
                />
                @error('form.category_description')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Character counter -->
            <div class="flex justify-end">
                <span class="text-xs text-slate-400" x-data="{ text: $wire.entangle('form.category_description') }" x-text="`${(text || '').length} / 200 حرف`"></span>
            </div>

        </div>

        {{-- Footer --}}
        <div class="relative px-6 py-5 mt-8 border-t border-slate-100 dark:border-slate-800">
            <div class="flex items-center justify-end gap-3">

                <flux:modal.close>
                    <flux:button
                        type="button"
                        class="group px-5 py-2.5 rounded-xl font-medium text-slate-600 dark:text-slate-400 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-700 hover:border-slate-300 transition-all duration-200"
                        :disabled="$isSaving"
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
                    class="px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 shadow-md hover:shadow-lg transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
                    :disabled="$isSaving"
                >
                    <div class="flex items-center gap-2">
                        @if($isSaving)
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري الاضافة...
                        @else
                            <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            اضافة التصنيف
                        @endif
                    </div>
                </flux:button>

            </div>
        </div>

        {{-- Decorative elements --}}
    <div style="z-index: 0;" class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-blue-100/40 to-indigo-100/40 dark:from-blue-900/10 dark:to-indigo-900/10 rounded-tr-full pointer-events-none z-0"></div>
        <div style="z-index: 0;" class="absolute top-20 right-0 w-20 h-20 bg-gradient-to-bl from-slate-100/40 to-blue-100/40 dark:from-slate-900/5 dark:to-blue-900/5 rounded-bl-full pointer-events-none"></div>

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

    [flux\:modal="add-category"] {
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
        background: #3b82f6;
        border-radius: 10px;
    }

    textarea::-webkit-scrollbar-thumb:hover {
        background: #2563eb;
    }
</style>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</div>
