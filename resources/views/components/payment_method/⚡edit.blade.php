<?php

use Livewire\Component;
use App\Livewire\Forms\PaymentMethodForm;
use Livewire\Attributes\On;
use App\Models\PaymentMethod;

new class extends Component {
    public PaymentMethodForm $form;

    #[On('editPaymentMethod')]
    public function editPaymentMethod($id)
    {
        $payment_method = PaymentMethod::findOrFail($id);
        $this->form->setPaymentMethod($payment_method);
        Flux::modal('edit_payment_method')->show();
    }

    public function updatePaymentMethod()
    {
        $this->form->update();
        Flux::modal('edit_payment_method')->close();
        session()->flash('warning', 'تم تحديث طريقة الدفع بنجاح');
        $this->redirectRoute('payment_methods', navigate: true);
    }

    // تم إزالة دالة confirmDelete الخاطئة لأنها تعرض نافذة التعديل بشكل غير صحيح
    // سيتم استخدام نافذة منفصلة للحذف كما هو موضح أدناه

    public function deletePaymentMethod()
    {
        $this->form->payment_method->delete();
        Flux::modal('delete_payment_method')->close();
        session()->flash('success', 'تم حذف طريقة الدفع بنجاح');
        $this->redirectRoute('payment_methods', navigate: true);
    }
};
?>

<div>
    {{-- نافذة تعديل طريقة الدفع --}}
    <flux:modal name="edit_payment_method" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="updatePaymentMethod">
            <div>
                <flux:heading size="lg">تعديل طريقة الدفع</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل طريقة الدفع</flux:text>
            </div>

            {{-- اسم طريقة الدفع --}}
            <flux:input label="اسم طريقة الدفع" placeholder="اسم طريقة الدفع" wire:model="form.payment_method_name"
                wire:dirty.class="ring-1 ring-yellow-400" />

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
                        تحديث طريقة الدفع
                    </flux:button>
                </div>
            </div>
        </form>
    </flux:modal>

    {{-- نافذة حذف طريقة الدفع (مثال إضافي للاكتمال) --}}
    <flux:modal name="delete_payment_method" class="md:w-120">
        <form wire:submit.prevent="deletePaymentMethod" class="space-y-6">
            <div>
                <flux:heading size="lg">تأكيد الحذف</flux:heading>
                <flux:text class="mt-2">
                    هل أنت متأكد من حذف طريقة الدفع "{{ $form->payment_method_name }}"؟
                    لا يمكن التراجع عن هذا الإجراء.
                </flux:text>
            </div>
            <div class="flex justify-end gap-3">
                <flux:modal.close>
                    <flux:button type="button" variant="primary" color="gray">إلغاء</flux:button>
                </flux:modal.close>
                <flux:button type="submit" variant="danger" color="red">حذف</flux:button>
            </div>
        </form>
    </flux:modal>
</div>
