<?php

use Livewire\Component;
use App\Livewire\Forms\CreateCustomer;
use Livewire\Attributes\On;
use App\Models\Customer;

new class extends Component {
    public CreateCustomer $form;

    #[On('editCustomer')]
    public function editCustomer($id)
    {
        //return dd($id);
        $customer = Customer::findOrFail($id);
        $this->form->setCustomer($customer);
        Flux::modal('edit-customer')->show();
    }

    public function updateCustomer()
    {
        $this->form->update();
        Flux::modal('edit-customer')->close();
        session()->flash('warning', 'تم تحديث بيانات العميل بنجاح');
        $this->redirectRoute('customers', navigate: true);
    }

    public function confirmDelete($id)
    {
        //return dd($id);
        $customer = Customer::findOrFail($id);
        $this->form->setCustomer($customer);
        Flux::modal('edit-customer')->show();
    }

    public function deleteBranch()
    {
        $this->form->customer->delete();
        Flux::modal('delete-customer')->close();
        session()->flash('success', 'تم حذف بيانات العميل بنجاح');
        $this->redirectRoute('customers', navigate: true);
    }
};
?>

<div>


    <flux:modal name="edit-customer" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="updateCustomer">
            <div>
                <flux:heading size="lg">تعديل بيانات العميل</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل بيانات العميل </flux:text>
            </div>

            <flux:input label="اسم العميل" placeholder="اسم العميل" wire:model="form.customer_name"
                wire:dirty.class="rind-1 ring-yellow-400" />

            <flux:input label="الهاتف" type="tel" placeholder="الهاتف" wire:model="form.contact_info"
                wire:dirty.class="rind-1 ring-yellow-400" />



            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">تعديل بيانات العميل
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
