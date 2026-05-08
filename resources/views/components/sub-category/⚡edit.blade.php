<?php

use Livewire\Component;
use App\Livewire\Forms\CreateSubCategory;
use Livewire\Attributes\On;
use App\Models\SubCategory;
use App\Models\Category;


new class extends Component {
    public CreateSubCategory $form;

    #[On('editSubCategory')]
    public function editSubCategory($id)
    {
        //return dd($id);
        $subCategory = SubCategory::findOrFail($id);
        $this->form->setSubCategory($subCategory);
        Flux::modal('edit-subCategory')->show();
    }

    public function updateSubCategory()
    {
        $this->form->update();
        Flux::modal('edit-subCategory')->close();
        session()->flash('warning', 'تم تحديث التصنيف الفرعي  بنجاح');
        $this->redirectRoute('subCategories', navigate: true);
    }

    public function confirmDelete($id)
    {
        //return dd($id);
        $expensesDetail = SubCategory::findOrFail($id);
        $this->form->setSubCategory($expensesDetail);
        Flux::modal('edit-subCategory')->show();
    }

    public function deleteSubCategory()
    {
        $this->form->subCategory->delete();
        Flux::modal('delete-subCategory')->close();
        session()->flash('success', 'تم حذف التصنيف الفرعي بنجاح');
        $this->redirectRoute('subCategories', navigate: true);
    }

        public function getCategoriesProperty()
    {
        return Category::select('id', 'category_name')->get();
    }
};
?>

<div>

    <flux:modal name="edit-subCategory" class="md:w-150">

        <form class="space-y-6" wire:submit.prevent="updateSubCategory">

            <!-- 🔹 Header -->
            <div>
                <flux:heading size="lg">تعديل التصنيف الفرعي</flux:heading>
                <flux:text class="mt-2">تعديل تفاصيل التصنيف الفرعي</flux:text>
            </div>

            <!-- 🔥 Select (Search شغال + مربوط) -->
            <flux:select searchable placeholder="اختر التصنيف الرئيسي" wire:model="form.category_id"
                wire:dirty.class="rind-1 ring-yellow-400">
                <flux:select.option value=""> اختر التصنيف الرئيسي </flux:select.option>
                @foreach ($this->categories as $category)
                    <flux:select.option value="{{ $category->id }}"> {{ $category->category_name }} </flux:select.option>
                @endforeach
            </flux:select>

             <!-- 🔹 التصنيف الفرعي -->
             <flux:input label="اسم التصنيف الفرعي" type="text" placeholder="ادخل اسم التصنيف الفرعي" wire:model="form.sub_category_name" 
             wire:dirty.class="rind-1 ring-yellow-400" />

             <!-- 🔹 وصف التصنيف الفرعي -->
             <flux:input label="وصف التصنيف الفرعي" placeholder="الوصف" wire:model="form.sub_category_description"
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
                        تعديل التصنيف الفرعي
                    </flux:button>
                </div>

            </div>

        </form>

    </flux:modal>

</div>
