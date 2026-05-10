<?php

use Livewire\Component;
use App\Livewire\Forms\CustomersForm;
use Livewire\Attributes\On;
use App\Models\Customer;

new class extends Component {
    public CustomersForm $form;
    public bool $isUpdating = false;

    #[On('editCustomer')]
    public function editCustomer($id)
    {
        $customer = Customer::findOrFail($id);
        $this->form->setCustomer($customer);
        Flux::modal('edit-customer')->show();
    }

    public function updateCustomer()
    {
        $this->isUpdating = true;

        try {
            $this->form->update();
            Flux::modal('edit-customer')->close();
            session()->flash('warning', 'تم تحديث بيانات العميل بنجاح');
            $this->redirectRoute('customers', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء تحديث بيانات العميل');
            $this->isUpdating = false;
        }
    }

    public function confirmDelete($id)
    {
        $customer = Customer::findOrFail($id);
        $this->form->setCustomer($customer);
        Flux::modal('delete-customer')->show();
    }

    public function deleteBranch()
    {
        $this->form->customer->delete();
        Flux::modal('delete-customer')->close();
        session()->flash('success', 'تم حذف بيانات العميل بنجاح');
        $this->redirectRoute('customers', navigate: true);
    }
};
?>

<div style="padding: 0;">

<flux:modal name="edit-customer" class="md:w-[580px] overflow-hidden rounded-2xl" style="padding: 0;">

    <form wire:submit.prevent="updateCustomer" class="relative">

        {{-- Background pattern --}}
        <div class="absolute inset-0 bg-gradient-to-br from-amber-50/50 via-orange-50/30 to-yellow-50/50 dark:from-amber-950/10 dark:via-orange-950/5 dark:to-yellow-950/10 pointer-events-none"></div>

        {{-- Decorative dots pattern --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, rgba(245,158,11,0.1) 1px, transparent 1px); background-size: 24px 24px;"></div>

        {{-- Header with edit indicator --}}
        <div class="relative px-6 pt-6 pb-4">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-amber-100 dark:bg-amber-900/50 text-amber-700 dark:text-amber-300">
                            تعديل
                        </span>
                        <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full bg-orange-100 dark:bg-orange-900/50 text-orange-700 dark:text-orange-300">
                            عميل
                        </span>
                    </div>

                    <flux:heading size="lg" class="font-bold text-slate-800 dark:text-white">
                        تعديل بيانات العميل
                    </flux:heading>

                    <flux:text class="mt-2 text-slate-500 dark:text-slate-400">
                        قم بتعديل تفاصيل بيانات العميل الحالي
                    </flux:text>
                </div>

                {{-- Animated edit icon --}}
                <div class="hidden sm:block">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 flex items-center justify-center shadow-sm">
                        <svg class="w-7 h-7 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body with form fields --}}
        <div class="relative px-6 space-y-6">

            <!-- Customer Name -->
            <div class="group">
                <flux:label class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    اسم العميل
                    <span class="text-red-500">*</span>
                </flux:label>
                <div class="relative">
                    <flux:input
                        placeholder="أدخل الاسم الكامل للعميل"
                        wire:model="form.customer_name"
                        wire:dirty.class="border-amber-400 ring-4 ring-amber-400/20"
                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-amber-400 focus:ring-4 focus:ring-amber-400/20 transition-all duration-200 pr-11"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-amber-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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

            <!-- Phone Number - Egyptian Format -->
            <div class="group">
                <flux:label class="mb-2 text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                    <svg class="w-4 h-4 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    رقم الهاتف
                    <span class="text-red-500">*</span>
                </flux:label>
                <div class="relative">

                    <flux:input
                        placeholder="1XXXXXXXXX"
                        wire:model="form.contact_info"
                        wire:dirty.class="border-amber-400 ring-4 ring-amber-400/20"
                        class="w-full px-4 py-3 rounded-xl border-2 border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 focus:border-orange-400 focus:ring-4 focus:ring-orange-400/20 transition-all duration-200 pr-11"
                        dir="ltr"
                    />
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400 group-focus-within:text-orange-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-xs text-slate-400 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    أدخل رقم الهاتف المصري (مثال: 1001234567 أو 01234567890)
                </p>
                @error('form.contact_info')
                    <p class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Dirty Indicator -->
            <div class="flex items-center justify-end" wire:dirty>
                <div class="flex items-center gap-2 text-xs">
                    <div class="flex items-center gap-1 text-amber-600 dark:text-amber-400">
                        <svg class="w-3.5 h-3.5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        <span>تم تعديل البيانات</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer with action buttons --}}
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
                            إلغاء
                        </div>
                    </flux:button>
                </flux:modal.close>

                <flux:button
                    type="submit"
                    class="px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-amber-600 via-orange-600 to-yellow-600 hover:from-amber-700 hover:via-orange-700 hover:to-yellow-700 shadow-md hover:shadow-xl transition-all duration-200 hover:scale-[1.02] active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed"
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
                            تعديل بيانات العميل
                        @endif
                    </div>
                </flux:button>

            </div>
        </div>

        {{-- Floating decorative elements --}}
        <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-amber-100/30 to-orange-100/30 dark:from-amber-900/10 dark:to-orange-900/10 rounded-tr-full pointer-events-none"></div>
        <div class="absolute top-24 right-0 w-24 h-24 bg-gradient-to-bl from-yellow-100/30 to-amber-100/30 dark:from-yellow-900/5 dark:to-amber-900/5 rounded-bl-full pointer-events-none"></div>

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

    [flux\:modal="edit-customer"] {
        animation: modalSlideUp 0.3s cubic-bezier(0.34, 1.2, 0.64, 1);
    }

    input:focus {
        outline: none;
    }

    button, input {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Phone input styling for Egyptian numbers */
    input[type="tel"] {
        direction: ltr;
        text-align: left;
        font-family: 'Courier New', monospace;
        letter-spacing: 0.5px;
    }

    /* RTL support for phone input */
    html[dir="rtl"] input[type="tel"] {
        text-align: right;
    }

    /* Dirty field highlight animation */
    [wire\:dirty] input {
        animation: dirtyPulse 1.5s ease-in-out 2;
    }

    /* Custom number input arrows */
    input[type="tel"]::-webkit-inner-spin-button,
    input[type="tel"]::-webkit-outer-spin-button {
        opacity: 0.5;
    }
</style>

</div>
