<?php

use Livewire\Component;
use App\Livewire\Forms\CustomersForm;

new class extends Component {
    public CustomersForm $form;
    public bool $isSaving = false;

    public function save()
    {
        $this->isSaving = true;

        try {
            $this->form->store();
            Flux::modal('add-customer')->close();
            session()->flash('success', 'تم اضافة عميل جديد بنجاح');
            $this->redirectRoute('customers', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء اضافة العميل');
            $this->isSaving = false;
        }
    }
};
?>

<div>

<flux:modal name="add-customer" class="md:w-[580px] overflow-hidden rounded-2xl" style="padding: 0;">

    <form wire:submit.prevent="save" class="relative">

        {{-- Background pattern --}}
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/50 via-teal-50/30 to-cyan-50/50 dark:from-emerald-950/10 dark:via-teal-950/5 dark:to-cyan-950/10 pointer-events-none"></div>

        {{-- Decorative dots pattern --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, rgba(16,185,129,0.1) 1px, transparent 1px); background-size: 24px 24px;"></div>

        {{-- Header with customer icon --}}
        <div class="relative px-6 pt-6 pb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300">
                            جديد
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-teal-100 dark:bg-teal-900/50 text-teal-700 dark:text-teal-300">
                            عميل
                        </span>
                    </div>

                    <flux:heading size="lg" class="font-bold text-slate-800 dark:text-white">
                        اضافة عميل جديد
                    </flux:heading>

                    <flux:text class="mt-2 text-slate-500 dark:text-slate-400">
                        قم بإضافة عميل جديد إلى قاعدة العملاء
                    </flux:text>
                </div>

                {{-- Animated icon --}}
                <div class="hidden sm:block">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-100 to-teal-100 dark:from-emerald-900/30 dark:to-teal-900/30 flex items-center justify-center shadow-sm group-hover:scale-105 transition-transform">
                        <svg class="w-7 h-7 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body with form fields --}}
        <div class="relative px-6 space-y-6">

            <!-- Customer Name with icon -->
            <div class="group">
                <flux:label class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    اسم العميل
                    <span class="text-red-500">*</span>
                </flux:label>
                <div class="relative">
                    <flux:input
                        placeholder="أدخل الاسم الكامل للعميل"
                        wire:model="form.customer_name"
                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-emerald-400 focus:ring-4 focus:ring-emerald-400/20 transition-all duration-200 pr-11"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-emerald-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                @error('form.customer_name')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Phone/Mobile with country code suggestion -->
            <div class="group">
                <flux:label class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-teal-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    رقم الموبايل
                    <span class="text-red-500">*</span>
                </flux:label>
                <div class="relative">

                    <flux:input
                        type="tel"
                        placeholder="5xxxxxxxx"
                        wire:model="form.contact_info"
                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-teal-400 focus:ring-4 focus:ring-teal-400/20 transition-all duration-200 pl-5 pr-11"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-teal-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-xs text-slate-400">أدخل رقم الجوال بدون مفتاح الدولة</p>
                @error('form.contact_info')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

        </div>

        {{-- Footer with action buttons --}}
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
                            إلغاء
                        </div>
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    class="px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-700 hover:via-teal-700 hover:to-cyan-700 shadow-md hover:shadow-xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
                    :disabled="$isSaving"
                >
                    <div class="flex items-center gap-2">
                        @if($isSaving)
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            جاري الإضافة...
                        @else
                            <svg class="w-4 h-4 group-hover:rotate-12 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                            </svg>
                            إضافة عميل جديد
                        @endif
                    </div>
                </flux:button>

            </div>
        </div>

        {{-- Floating decorative elements --}}
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-emerald-100/30 to-teal-100/30 dark:from-emerald-900/10 dark:to-teal-900/10 rounded-tr-full pointer-events-none"></div>
        <div class="absolute top-24 right-0 w-24 h-24 bg-gradient-to-bl from-cyan-100/30 to-emerald-100/30 dark:from-cyan-900/5 dark:to-emerald-900/5 rounded-bl-full pointer-events-none"></div>

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

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    [flux\:modal="add-customer"] {
        animation: modalSlideUp 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
    }

    input:focus, textarea:focus {
        outline: none;
    }

    button, input {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Custom number input styling */
    input[type="tel"] {
        direction: ltr;
        text-align: left;
    }

    /* RTL support for phone input */
    html[dir="rtl"] input[type="tel"] {
        text-align: right;
    }

    /* Floating label animation */
    .group:focus-within label {
        color: #10b981;
    }
</style>

</div>
