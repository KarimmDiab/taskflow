<?php

use Livewire\Component;
use App\Livewire\Forms\CreateCategory;

new class extends Component {
    public CreateCategory $form;
    public function save()
    {
        $this->form->store();
        Flux::modal('add-category')->close();

        session()->flash('success', 'تم اضافة التصنيف بنجاح');

        $this->redirectRoute('categories', navigate: true);
    }
};
?>

<div>


    <flux:modal name="add-category" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="save">
            <div>
                <flux:heading size="lg">اضافة تصنيف جديد</flux:heading>
                <flux:text class="mt-2">إنشاء تصنيف جديد مع جميع التفاصيل</flux:text>
            </div>

            <flux:input label="اسم تصنيف" placeholder="اسم تصنيف" wire:model="form.category_name" />

            <flux:input label="وصف التصنيف" type="text" placeholder="وصف التصنيف"
                wire:model="form.category_description" />

            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">اضافة التصنيف
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
