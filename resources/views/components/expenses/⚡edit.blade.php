<?php

use Livewire\Component;
use App\Livewire\Forms\ExpensesForm;
use Livewire\Attributes\On;
use App\Models\ExpensesDetail;

new class extends Component {
    public ExpensesForm $form;

    #[On('editExpensesDetail')]
    public function editExpensesDetail($id)
    {
        //return dd($id);
        $expensesDetail = ExpensesDetail::findOrFail($id);
        $this->form->setExpensesDetail($expensesDetail);
        Flux::modal('edit-expensesDetail')->show();
    }

    public function updateExpensesDetail()
    {
        $this->form->update();
        Flux::modal('edit-expensesDetail')->close();
        session()->flash('warning', 'تم تحديث بيانات هذا المصروف بنجاح');
        $this->redirectRoute('expenses', navigate: true);
    }

    public function confirmDelete($id)
    {
        //return dd($id);
        $expensesDetail = ExpensesDetail::findOrFail($id);
        $this->form->setExpensesDetail($expensesDetail);
        Flux::modal('edit-expensesDetail')->show();
    }

    public function deleteExpensesDetail()
    {
        $this->form->expensesDetail->delete();
        Flux::modal('delete-expensesDetail')->close();
        session()->flash('success', 'تم حذف بيانات هذا المصروف بنجاح');
        $this->redirectRoute('expenses', navigate: true);
    }
};
?>

<div>

    <flux:modal name="edit-expensesDetail" class="md:w-150">

        <form class="space-y-6" wire:submit.prevent="updateExpensesDetail">

            <!-- 🔹 Header -->
            <div>
                <flux:heading size="lg">تعديل المصروف</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل المصروف</flux:text>
            </div>

            <!-- 🔥 Select (Search شغال + مربوط) -->
            <flux:select searchable placeholder="اختر بند المصروف" wire:model="form.expenses_item_id"
                wire:dirty.class="rind-1 ring-yellow-400">
                <flux:select.option value=""> اختر بند المصروف </flux:select.option>
                @foreach ($this->expensesItems as $item)
                    <flux:select.option value="{{ $item->id }}"> {{ $item->expenses_name }} </flux:select.option>
                @endforeach
            </flux:select>

            <!-- 🔹 التكلفة -->
            <flux:input label="التكلفة" type="number" placeholder="ادخل التكلفة" wire:model="form.expenses_cost"
                wire:dirty.class="rind-1 ring-yellow-400" />

            <!-- 🔹 الملاحظات -->
            <flux:input label="الملحوظات" placeholder="ادخل الملاحظات" wire:model="form.expenses_note"
                wire:dirty.class="rind-1 ring-yellow-400" />

            <!-- 🔥 التاريخ (بدل input العادي) -->
            <flux:input label="التاريخ" type="date" wire:model="form.expenses_date" dir="rtl"
                wire:dirty.class="rind-1 ring-yellow-400" />


            <!-- 🔹 الأزرار -->
            <div class="grid grid-cols-3 items-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="button" variant="danger">
                            الغاء
                        </flux:button>
                    </flux:modal.close>
                </div>

                <div></div>

                <div class="text-right">
                    <flux:button type="submit" variant="primary" color="green">
                        اضافة مصروف
                    </flux:button>
                </div>

            </div>

        </form>

    </flux:modal>

</div>
