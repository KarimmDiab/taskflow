<?php

use Livewire\Component;
use App\Livewire\Forms\CreateBranch;

new class extends Component {
    public CreateBranch $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add-branch')->close();

        session()->flash('success', 'تم اضافة الفرع بنجاح');

        $this->redirectRoute('branches', navigate: true);
    }
};
?>

<div>


    <flux:modal name="add-branch" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">اضافة فرع جديد</flux:heading>
                <flux:text class="mt-2">إنشاء فرع جديد مع جميع التفاصيل</flux:text>
            </div>

            <flux:input label="اسم الفرع" placeholder="اسم الفرع" wire:model="form.branch_name" />

            <flux:input label="عنوان الفرع" type="text" placeholder="عنوان الفرع"
                wire:model="form.branch_address" />

            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">اضافة الفرع
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
