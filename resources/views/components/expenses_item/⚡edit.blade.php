<?php

use Livewire\Component;
use App\Livewire\Forms\CreateExpensesItem;
use Livewire\Attributes\On;
use App\Models\ExpensesItem;

new class extends Component
{
    public CreateExpensesItem $form;

    #[On('editExpensesItem')]
    public function editExpensesItem($id)
    {
        //return dd($id);
        $expenses_item = ExpensesItem::findOrFail($id);
        $this->form->setExpensesItem($expenses_item);
        Flux::modal('edit_expenses_item')->show();
    }

    public function updateExpensesItem()
    {
        $this->form->update();
        Flux::modal('edit_expenses_item')->close();
        session()->flash('warning', 'تم تحديث بند المصروفات بنجاح');
        $this->redirectRoute('expenses_items', navigate:true);
    }

        public function confirmDelete($id)
    {
        //return dd($id);
        $expenses_item = ExpensesItem::findOrFail($id);
        $this->form->setExpensesItem($expenses_item);
        Flux::modal('edit_expenses_item')->show();
    }


    public function deleteExpensesItem()
    {
        $this->form->expenses_item->delete();
        Flux::modal('delete_expenses_item')->close();
        session()->flash('success', 'تم حذف بند المصروفات بنجاح');
        $this->redirectRoute('expenses_items', navigate:true);
    }


};
?>

<div>


    <flux:modal name="edit_expenses_item" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="updateExpensesItem">
            <div>
                <flux:heading size="lg">تعديل بند المصروفات</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل بند المصروفات</flux:text>
            </div>

            <flux:input label="اسم البند" placeholder="اسم البند" wire:model="form.expenses_name" wire:dirty.class="rind-1 ring-yellow-400" />


            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">تحديث البند
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
