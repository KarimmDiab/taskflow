<?php

use Livewire\Component;
use App\Livewire\Forms\CreateCustomer;

new class extends Component {
    public CreateCustomer $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add-customer')->close();

        session()->flash('success', 'تم اضافة عميل جديد بنجاح');

        $this->redirectRoute('customers', navigate: true);
    }
};
?>

<div>


    <flux:modal name="add-customer" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">اضافة عميل جديد</flux:heading>
                <flux:text class="mt-2">اضافة عميل جديد مع جميع التفاصيل</flux:text>
            </div>

            <flux:input label="اسم العميل" placeholder="اسم العميل" wire:model="form.customer_name" />

            <flux:input label="الموبايل" type="tel" placeholder="الموبايل" wire:model="form.contact_info" />





            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">اضافة عميل جديد
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
