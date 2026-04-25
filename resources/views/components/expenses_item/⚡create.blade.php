<?php

use Livewire\Component;
use App\Livewire\Forms\CreateExpensesItem;

new class extends Component {
    public CreateExpensesItem $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add_expenses_item')->close();

        session()->flash('success', 'تم اضافة بند المصروفات بنجاح');

        $this->redirectRoute('expenses_items', navigate: true);
    }
};
?>

<div>


    <flux:modal name="add_expenses_item" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">اضافة بند مصروفات جديد</flux:heading>
                <flux:text class="mt-2">إنشاء بند مصروفات جديد مع جميع التفاصيل</flux:text>
            </div>

            <flux:input label="اسم البند" placeholder="اسم البند" wire:model="form.expenses_name" />



            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">اضافة البند
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
