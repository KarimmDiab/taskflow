<?php

use Livewire\Component;
use App\Livewire\Forms\CreateSupplier;
use Livewire\Attributes\On;
use App\Models\Supplier;

new class extends Component {
    public CreateSupplier $form;

    #[On('editSupplier')]
    public function editSupplier($id)
    {
        //return dd($id);
        $supplier = Supplier::findOrFail($id);
        $this->form->setSupplier($supplier);
        Flux::modal('edit-supplier')->show();
    }

    public function updateSupplier()
    {
        $this->form->update();
        Flux::modal('edit-supplier')->close();
        session()->flash('warning', 'تم تحديث بيانات المورد بنجاح');
        $this->redirectRoute('suppliers', navigate: true);
    }

    public function confirmDelete($id)
    {
        //return dd($id);
        $user = Supplier::findOrFail($id);
        $this->form->setSupplier($user);
        Flux::modal('edit-supplier')->show();
    }

    public function deleteBranch()
    {
        $this->form->supplier->delete();
        Flux::modal('delete-supplier')->close();
        session()->flash('success', 'تم حذف بيانات المورد بنجاح');
        $this->redirectRoute('suppliers', navigate: true);
    }
};
?>

<div>


    <flux:modal name="edit-supplier" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="updateSupplier">
            <div>
                <flux:heading size="lg">تعديل بيانات المورد</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل بيانات المورد </flux:text>
            </div>

            <flux:input label="اسم المورد" placeholder="اسم المورد" wire:model="form.supplier_name"
                wire:dirty.class="rind-1 ring-yellow-400" />

            <flux:input label="الهاتف" type="tel" placeholder="الهاتف" wire:model="form.supplier_phone"
                wire:dirty.class="rind-1 ring-yellow-400" />

            <flux:input label="العنوان" placeholder="العنوان" wire:model="form.supplier_address"
                wire:dirty.class="rind-1 ring-yellow-400" />




            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">تعديل بيانات المورد
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
