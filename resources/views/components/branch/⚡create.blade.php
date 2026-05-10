<?php

use Livewire\Component;
use App\Livewire\Forms\BranchForm;

new class extends Component {
    public BranchForm $form;
    public bool $isSaving = false;

    public function save()
    {
        $this->isSaving = true;

        try {
            $this->form->store();
            Flux::modal('add-branch')->close();
            session()->flash('success', 'تم اضافة الفرع بنجاح');
            $this->redirectRoute('branches', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء اضافة الفرع');
            $this->isSaving = false;
        }
    }
};
?>


<div>

<flux:modal name="add-branch" class="md:w-[600px] overflow-hidden rounded-2xl" style="padding: 0;">

    <form wire:submit.prevent="save" class="relative">

        {{-- Decorative accent bar --}}
        <div class="absolute top-0 right-0 w-1 h-full bg-gradient-to-b from-emerald-500 to-teal-600"></div>

        {{-- Header --}}
        <div class="bg-gradient-to-r from-gray-50 to-white dark:from-zinc-800 dark:to-zinc-800/90 px-6 py-5 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 rounded-xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <flux:heading size="lg" class="font-bold text-gray-900 dark:text-white">
                        اضافة فرع جديد
                    </flux:heading>
                    <flux:text class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        قم بإدخال تفاصيل الفرع لإضافته إلى النظام
                    </flux:text>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-6 bg-white dark:bg-zinc-900">

            <!-- Branch Name -->
            <div>
                <flux:label class="mb-1.5 font-semibold text-gray-700 dark:text-gray-300">
                    اسم الفرع <span class="text-red-500 dark:text-red-400">*</span>
                </flux:label>
                <flux:input
                    placeholder="مثال: فرع الرياض الرئيسي"
                    wire:model="form.branch_name"
                    class="w-full px-4 py-2.5 rounded-xl border-gray-300 dark:border-zinc-600 focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200"
                    style="border-width: 1px;"
                />
                @error('form.branch_name')
                    <flux:error class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </flux:error>
                @enderror
            </div>

            <!-- Branch Address -->
            <div>
                <flux:label class="mb-1.5 font-semibold text-gray-700 dark:text-gray-300">
                    عنوان الفرع <span class="text-red-500 dark:text-red-400">*</span>
                </flux:label>
                <flux:textarea
                    placeholder="مثال: حي الملك فهد، شارع التحلية، مبنى رقم ١٢٣"
                    wire:model="form.branch_address"
                    rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border-gray-300 dark:border-zinc-600 focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200 resize-none"
                    style="border-width: 1px;"
                />
                @error('form.branch_address')
                    <flux:error class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </flux:error>
                @enderror
            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 dark:bg-zinc-800/50 px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between gap-3">

                <flux:modal.close>
                    <flux:button
                        type="button"
                        class="px-5 py-2.5 rounded-xl font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:border-red-300 dark:hover:border-red-700 transition-all duration-200"
                        :disabled="$isSaving"
                    >
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            الغاء
                        </div>
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    class="px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
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
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            اضافة الفرع
                        @endif
                    </div>
                </flux:button>

            </div>
        </div>

    </form>

</flux:modal>

{{-- Optional: Add custom CSS for better animations --}}
<style>
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: scale(0.96) translateY(-10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    [flux\:modal="add-branch"] {
        animation: slideIn 0.2s ease-out;
    }

    /* Improved focus styles */
    input:focus, textarea:focus {
        outline: none;
        ring: 2px solid rgb(16 185 129);
        ring-offset: 2px;
    }

    /* Smooth transitions */
    button, input, textarea {
        transition: all 0.2s ease;
    }
</style>
</div>
