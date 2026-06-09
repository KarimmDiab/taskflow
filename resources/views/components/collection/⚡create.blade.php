<?php

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Livewire\Forms\CollectionForm;
use Flux\Flux;
use Illuminate\Support\Facades\Log;

new class extends Component {
    use WithFileUploads;

    public CollectionForm $form;
    public bool $isSaving = false;

    public function save()
    {
        $this->isSaving = true;

        try {
            if ($this->form->collection) {
                $this->form->update();
                session()->flash('success', 'تم تحديث الكولكشن بنجاح');
            } else {
                $this->form->store();
                session()->flash('success', 'تم اضافة الكولكشن بنجاح');
            }

            Flux::modal('add-collection')->close();
            $this->form->resetForm();

            // التوجيه إلى صفحة المجموعات
            $this->redirectRoute('all_collections', navigate: true);

        } catch (\Exception $e) {
            Log::error('Collection save error: ' . $e->getMessage());
            session()->flash('error', 'حدث خطأ أثناء حفظ الكولكشن');
            $this->isSaving = false;
        }
    }
};
?>

<flux:modal name="add-collection" class="md:w-[600px] overflow-hidden rounded-2xl" style="padding: 0;">

    <form wire:submit.prevent="save" enctype="multipart/form-data" class="relative">

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
                        اضافة كولكشن جديد
                    </flux:heading>
                    <flux:text class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        قم بإدخال تفاصيل الكولكشن لإضافته إلى النظام
                    </flux:text>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-6 space-y-6 bg-white dark:bg-zinc-900">

            <!-- collection_name -->
            <div>
                <flux:label class="mb-1.5 font-semibold text-gray-700 dark:text-gray-300">
                    اسم الكولكشن <span class="text-red-500 dark:text-red-400">*</span>
                </flux:label>
                <flux:input
                    placeholder="مثال: Summer Collection"
                    wire:model="form.collection_name"
                    class="w-full px-4 py-2.5 rounded-xl border-gray-300 dark:border-zinc-600 focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200"
                    style="border-width: 1px;"
                />
                @error('form.collection_name')
                    <flux:error class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </flux:error>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <flux:label class="mb-1.5 font-semibold text-gray-700 dark:text-gray-300">
                    صورة الكولكشن
                </flux:label>
                <div class="flex items-center gap-4 flex-wrap">
                    <label class="cursor-pointer">
                        <input type="file" wire:model="form.main_image" accept="image/*" class="hidden" />
                        <div class="px-4 py-2 rounded-xl border border-gray-300 dark:border-zinc-600 bg-gray-50 dark:bg-zinc-800 hover:bg-gray-100 dark:hover:bg-zinc-700 transition flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            اختر صورة
                        </div>
                    </label>
                    @if($form->main_image && !$form->existing_image)
                        <span class="text-xs text-emerald-600 dark:text-emerald-400">✓ سيتم رفع الصورة الجديدة</span>
                    @elseif($form->existing_image && !$form->main_image)
                        <span class="text-xs text-gray-500 dark:text-gray-400">الصورة الحالية محفوظة</span>
                    @elseif($form->main_image && $form->existing_image)
                        <span class="text-xs text-amber-600 dark:text-amber-400">سيتم استبدال الصورة القديمة</span>
                    @endif
                </div>
                @if($form->existing_image && !$form->main_image)
                    <div class="mt-2">
                        <img src="{{ Storage::url($form->existing_image) }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200 dark:border-zinc-700">
                    </div>
                @endif
                @error('form.main_image')
                    <flux:error class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </flux:error>
                @enderror
            </div>

            <!-- collection_desc -->
            <div>
                <flux:label class="mb-1.5 font-semibold text-gray-700 dark:text-gray-300">
                    وصف او قصة الكولكشن
                </flux:label>
                <flux:input
                    wire:model="form.collection_desc"
                    class="w-full px-4 py-2.5 rounded-xl border-gray-300 dark:border-zinc-600 focus:border-emerald-500 focus:ring-emerald-500 transition-all duration-200"
                    style="border-width: 1px;"
                />
                @error('form.collection_desc')
                    <flux:error class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $message }}
                    </flux:error>
                @enderror
            </div>

            <!-- Active Status (Toggle Switch) -->
            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 dark:bg-zinc-800/50 border border-gray-200 dark:border-zinc-700">
                <div>
                    <flux:label class="font-semibold text-gray-700 dark:text-gray-300">
                        حالة الكولكشن
                    </flux:label>
                    <flux:text class="text-xs text-gray-500 dark:text-gray-400">
                        تعطيل الكولكشن يجعله غير متاح للاختيار في النظام
                    </flux:text>
                </div>
                <div class="relative">
                    <input type="checkbox"
                           wire:model="form.is_active"
                           id="is_active_toggle"
                           class="sr-only peer"
                           @if($form->is_active) checked @endif>
                    <label for="is_active_toggle"
                           class="block w-12 h-6 bg-gray-300 dark:bg-zinc-600 rounded-full peer-checked:bg-emerald-500 peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:right-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:right-auto peer-checked:after:left-[2px] cursor-pointer">
                    </label>
                </div>
                @error('form.is_active')
                    <flux:error class="mt-1.5 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
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
                            اضافة الكولكشن
                        @endif
                    </div>
                </flux:button>

            </div>
        </div>

    </form>

</flux:modal>

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


    [flux\:modal="add-collection"] {

        animation: slideIn 0.2s ease-out;
    }


    /* Focus styles */

    input:focus,
    textarea:focus {

        outline: none !important;

        border-color: rgb(16 185 129) !important;

        box-shadow:
            0 0 0 2px rgba(16, 185, 129, 0.15) !important;
    }


    /* Smooth transitions */

    button,
    input,
    textarea,
    label {

        transition:
            all 0.2s ease;
    }


    /* Image preview */

    img {

        transition:
            transform 0.3s ease,
            opacity 0.3s ease;
    }

    img:hover {

        transform: scale(1.03);
    }


    /* Modal scrollbar */

    .overflow-hidden {

        scrollbar-width: thin;
    }


    /* Better disabled state */

    button:disabled {

        opacity: 0.6;
        cursor: not-allowed;
    }


    /* File upload hover */

    label.cursor-pointer:hover div {

        transform: translateY(-1px);
    }

</style>


