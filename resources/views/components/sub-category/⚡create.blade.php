<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\CreateSubCategory;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Flux\Flux;

new class extends Component {
    use WithPagination;

    public CreateSubCategory $form;

    public string $search = '';
    public int $perPage = 10;

    public function getSubCategoryProperty()
    {
        return SubCategory::query()
            ->with(['category'])
            ->when($this->search, function (Builder $q) {
                $q->where(function (Builder $query) {
                    $query
                        ->where('sub_category_name', 'like', "%{$this->search}%" , 'sub_category_description', 'like', "%{$this->search}%")
                        ->orWhereHas('category', function (Builder $q) {
                            $q->where('category_name', 'like', "%{$this->search}%");
                        });
                });
            })
            ->latest()
            ->paginate($this->perPage);
    }

    public function getCategoriesProperty()
    {
        return Category::select('id', 'category_name')->get();
    }

    public function save()
    {
        $this->form->store();

        Flux::modal('add-subCategory')->close();

        session()->flash('success', 'تم اضافة تصنميف فرعي جديد بنجاح');

        $this->redirectRoute('subCategories', navigate: true);
    }
};
?>

<div>

    <flux:modal name="add-subCategory" class="md:w-150">

        <form class="space-y-6" wire:submit.prevent="save">

            <!-- 🔹 Header -->
            <div>
                <flux:heading size="lg">اضافة تصنيف فرعي جديد</flux:heading>
                <flux:text class="mt-2">تصنيف فرعي جديد مع جميع التفاصيل</flux:text>
            </div>

            <!-- 🔥 Select (Search شغال + مربوط) -->
            <flux:select searchable placeholder="اختر التصنيف الرئيسي" wire:model="form.category_id">
                <flux:select.option value=""> اختر التصنيف الرئيسي </flux:select.option>
                @foreach ($this->categories as $category)
                    <flux:select.option value="{{ $category->id }}"> {{ $category->category_name }} </flux:select.option>
                @endforeach
            </flux:select>

            <!-- 🔹 التصنيف الفرعي -->
            <flux:input label="اسم التصنيف الفرعي" type="text" placeholder="ادخل اسم التصنيف الفرعي" wire:model="form.sub_category_name" />

            <!-- 🔹 وصف التصنيف الفرعي -->
            <flux:input label="وصف التصنيف الفرعي" placeholder="الوصف" wire:model="form.sub_category_description" />

          
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
                        اضافة التصنيف الفرعي
                    </flux:button>
                </div>

            </div>

        </form>

    </flux:modal>

</div>
