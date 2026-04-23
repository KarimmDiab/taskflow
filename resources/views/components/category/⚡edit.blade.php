<?php

use Livewire\Component;
use App\Livewire\Forms\CreateCategory;
use Livewire\Attributes\On;
use App\Models\Category;

new class extends Component
{
    public CreateCategory $form;

    #[On('editCategory')]
    public function editCategory($id)
    {
        //return dd($id);
        $category = Category::findOrFail($id);
        $this->form->setCategory($category);
        Flux::modal('edit-category')->show();
    }

    public function updateCategory()
    {
        $this->form->update();
        Flux::modal('edit-category')->close();
        session()->flash('warning', 'تم تحديث بيانات التصنيف بنجاح');
        $this->redirectRoute('categories', navigate:true);
    }

        public function confirmDelete($id)
    {
        //return dd($id);
        $category = Category::findOrFail($id);
        $this->form->setCategory($category);
        Flux::modal('edit-category')->show();
    }


    public function deleteCategory()
    {
        $this->form->category->delete();
        Flux::modal('delete-category')->close();
        session()->flash('success', 'تم حذف بيانات التصنيف بنجاح');
        $this->redirectRoute('categories', navigate:true);
    }


};
?>

<div>


    <flux:modal name="edit-category" class="md:w-150">
        <form class="space-y-8" wire:submit.prevent="updateCategory">
            <div>
                <flux:heading size="lg">تعديل التصنيف</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل التصنيف</flux:text>
            </div>

            <flux:input label="اسم التصنيف" placeholder="اسم التصنيف" wire:model="form.category_name" wire:dirty.class="rind-1 ring-yellow-400" />

            <flux:input label="وصف التصنيف" type="text" placeholder="وصف التصنيف" wire:model="form.category_description" wire:dirty.class="rind-1 ring-yellow-400"/>

            <div class="flex grid grid-cols-3 justify-center">

                <div>
                    <flux:modal.close>
                        <flux:button type="submit" variant="primary" color="red">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div>
                    <flux:button class="float-right" type="submit" variant="primary" color="green">تعديل التصنيف
                    </flux:button>
                </div>

            </div>
        </form>
    </flux:modal>
</div>
