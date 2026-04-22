<?php

use Livewire\Component;
use App\Livewire\Forms\CreateSupplier;

new class extends Component {
    public CreateSupplier $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add-supplier')->close();

        session()->flash('success', 'تم اضافة مورد جديد بنجاح');

        $this->redirectRoute('suppliers', navigate: true);
    }
};
?>

<div>


    <flux:modal name="add-supplier" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">اضافة مورد جديد</flux:heading>
                <flux:text class="mt-2">اضافةمورد جديد مع جميع التفاصيل</flux:text>
            </div>

            <flux:input label="اسم المورد" placeholder="اسم المورد" wire:model="form.supplier_name" />

            <flux:input label="الموبايل" type="tel" placeholder="الموبايل" wire:model="form.supplier_phone" />

            <flux:input label="العنوان" placeholder="العنوان" wire:model="form.supplier_address" />




            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">اضافة مورد جديد
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
