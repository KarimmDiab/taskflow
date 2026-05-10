<?php

use Livewire\Component;
use Livewire\WithPagination;
use App\Livewire\Forms\ProductForm;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Eloquent\Builder;
use Flux\Flux;

new class extends Component {
    use WithPagination;

    public ProductForm $form;

    public string $search = '';
    public int $perPage = 10;

    public function getProductsProperty()
    {
        return Product::query()
            ->with(['expensesItem', 'user'])
            ->when($this->search, function (Builder $q) {
                $q->where(function (Builder $query) {
                    $query
                        ->where('product_name', 'like', "%{$this->search}%")
                        ->orWhereHas('category', function (Builder $q) {
                            $q->where('category_id', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('branch', function (Builder $q) {
                            $q->where('branch_id', 'like', "%{$this->search}%");
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

    public function getSubCategoriesProperty()
    {
        return SubCategory::select('id', 'sub_category_name')->get();
    }

    public function save()
    {
        $this->form->store();

        Flux::modal('add-product')->close();

        session()->flash('success', 'تم اضافة منتج جديد بنجاح');

        $this->redirectRoute('products', navigate: true);
    }
};
?>

<div>

    <flux:modal name="add-product" class="md:w-150">

        <form class="space-y-6" wire:submit.prevent="save">

            <!-- 🔹 Header -->
            <div>
                <flux:heading size="lg">اضافة منتج جديد</flux:heading>
                <flux:text class="mt-2">منتج جديد مع جميع التفاصيل</flux:text>
            </div>

            <!-- 🔥 Select (Search شغال + مربوط) -->
            <flux:select label="اختر تصنيف المنتج" searchable placeholder="اختر التصنيف" wire:model="form.category_id">
                <flux:select.option value=""> اختر التصنيف </flux:select.option>
                @foreach ($this->categories as $category)
                    <flux:select.option value="{{ $category->id }}"> {{ $category->category_name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <!-- 🔹 التصنيف الفرعي -->
            <flux:select label="اختر التصنيف الفرعي" searchable placeholder="اختر التصنيف الفرعي" wire:model="form.sub_category_id">
                <flux:select.option value=""> اختر التصنيف الفرعي </flux:select.option>
                @foreach ($this->subCategories as $subCategory)
                    <flux:select.option value="{{ $subCategory->id }}"> {{ $subCategory->sub_category_name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <!-- 🔹 اسم المنتج -->
            <flux:input label="اسم المنتج" type="text" placeholder="ادخل اسم المنتج"
                wire:model="form.product_name" />

            <!-- 🔹 الكمية -->
            <flux:input label="الكمية" type="number" placeholder="ادخل الكمية" wire:model="form.product_quantity" />

            <!-- 🔹 سعر التكلفة -->
            <flux:input label="تكلفة المنتج" type="number" placeholder="تكلفة المنتج" wire:model="form.product_cost" />

            <!-- 🔹 السعر -->
            <flux:input label="سعر المنتج" type="number" placeholder="سعر المنتج" wire:model="form.product_price" />

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
                        اضافة منتج
                    </flux:button>
                </div>

            </div>

        </form>

    </flux:modal>

</div>
