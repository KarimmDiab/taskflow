<?php

use Livewire\Component;
use App\Livewire\Forms\PaymentMethodForm;

new class extends Component {
    public PaymentMethodForm $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add_payment_method')->close();

        session()->flash('success', 'تم إضافة طريقة دفع بنجاح');

        $this->redirectRoute('payment_methods', navigate: true);
    }
};
?>

<div>
    <flux:modal name="add_payment_method" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">إضافة طريقة دفع جديدة</flux:heading>
                <flux:text class="mt-2">إنشاء طريقة دفع جديدة مع جميع التفاصيل</flux:text>
            </div>

            {{-- اسم طريقة الدفع --}}
            <flux:input label="اسم طريقة الدفع" placeholder="أدخل اسم طريقة الدفع" wire:model="form.payment_method_name" />

            {{-- حالة التفعيل --}}
            <div class="flex items-center justify-between">
                <flux:label>حالة طريقة الدفع</flux:label>
                <flux:switch wire:model="form.is_active" />
            </div>
            
            <div class="flex grid grid-cols-3 justify-center">
                <div>
                    <flux:modal.close>
                        <flux:button type="button" variant="primary" color="red">إلغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">
                        إضافة طريقة الدفع
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
