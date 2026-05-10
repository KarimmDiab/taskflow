<?php

use Livewire\Component;
use App\Livewire\Forms\BranchForm;
use Livewire\Attributes\On;
use App\Models\Branches;

new class extends Component
{
    public BranchForm $form;
    public bool $isUpdating = false;

    #[On('editBranch')]
    public function editBranch($id)
    {
        $branch = Branches::findOrFail($id);
        $this->form->setBranch($branch);
        Flux::modal('edit-branch')->show();
    }

    public function updatebranch()
    {
        $this->isUpdating = true;

        try {
            $this->form->update();
            Flux::modal('edit-branch')->close();
            session()->flash('warning', 'تم تحديث بيانات الفرع بنجاح');
            $this->redirectRoute('branches', navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'حدث خطأ أثناء تحديث الفرع');
            $this->isUpdating = false;
        }
    }

    public function confirmDelete($id)
    {
        $branch = Branches::findOrFail($id);
        $this->form->setBranch($branch);
        Flux::modal('delete-branch')->show();
    }

    public function deleteBranch()
    {
        $this->form->branch->delete();
        Flux::modal('delete-branch')->close();
        session()->flash('success', 'تم حذف بيانات الفرع بنجاح');
        $this->redirectRoute('branches', navigate: true);
    }
};
?>

<div>

<flux:modal name="edit-branch" class="md:w-[600px] overflow-hidden rounded-2xl" style="padding: 0;">

    <form wire:submit.prevent="updatebranch" class="relative">

        {{-- Decorative accent bar --}}
        <div class="absolute top-0 right-0 w-1 h-full bg-gradient-to-b from-amber-500 to-orange-600"></div>

        {{-- Header --}}
        <div class="bg-gradient-to-r from-gray-50 to-white dark:from-zinc-800 dark:to-zinc-800/90 px-6 py-5 border-b border-gray-200 dark:border-zinc-700">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-gradient-to-br from-amber-100 to-orange-100 dark:from-amber-900/30 dark:to-orange-900/30 rounded-xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div>
                    <flux:heading size="lg" class="font-bold text-gray-900 dark:text-white">
                        تعديل الفرع
                    </flux:heading>
                    <flux:text class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        تعديل تفاصيل الفرع الحالي
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
                    wire:dirty.class="border-amber-400 ring-2 ring-amber-400/50"
                    class="w-full px-4 py-2.5 rounded-xl border-gray-300 dark:border-zinc-600 focus:border-amber-500 focus:ring-amber-500 transition-all duration-200"
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
                    wire:dirty.class="border-amber-400 ring-2 ring-amber-400/50"
                    rows="3"
                    class="w-full px-4 py-2.5 rounded-xl border-gray-300 dark:border-zinc-600 focus:border-amber-500 focus:ring-amber-500 transition-all duration-200 resize-none"
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

            <!-- Dirty Indicator (Optional) -->
            <div class="flex items-center justify-end gap-2 text-xs" wire:dirty>
                <div class="flex items-center gap-1 text-amber-600 dark:text-amber-400">
                    <svg class="w-3.5 h-3.5 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    <span>تم تعديل البيانات</span>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 dark:bg-zinc-800/50 px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
            <div class="flex items-center justify-between gap-3">

                <flux:modal.close>
                    <flux:button
                        type="button"
                        class="px-5 py-2.5 rounded-xl font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-zinc-800 border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 hover:border-red-300 dark:hover:border-red-700 transition-all duration-200"
                        :disabled="$isUpdating"
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
                    class="px-6 py-2.5 rounded-xl font-semibold text-white bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-700 hover:to-orange-700 shadow-md hover:shadow-lg transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
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
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            تحديث الفرع
                        @endif
                    </div>
                </flux:button>

            </div>
        </div>

    </form>

</flux:modal>

{{-- Optional: Custom CSS for better animations --}}
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

    [flux\:modal="edit-branch"] {
        animation: slideIn 0.2s ease-out;
    }

    /* Improved focus styles */
    input:focus, textarea:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
    }

    /* Smooth transitions */
    button, input, textarea {
        transition: all 0.2s ease;
    }

    /* Dirty field highlight animation */
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

    [wire\:dirty] input, [wire\:dirty] textarea {
        animation: dirtyPulse 1.5s ease-in-out 2;
    }
</style>
</div>
