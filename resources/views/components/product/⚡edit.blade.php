<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Product;
use App\Models\Branches;
use App\Models\Category;

// Create this file as app/Livewire/Forms/ProductForm.php if not exists
// Or define the form inline
new class extends Component {
    public $product_id;
    public $category_id;
    public $branch_id;
    public $product_name;
    public $product_quantity;
    public $product_cost;
    public $product_price;

    public function getCategoriesProperty()
    {
        return Category::select('id', 'category_name')->get();
    }

    public function getBranchesProperty()
    {
        return Branches::select('id', 'branch_name')->get();
    }

    #[On('openEditModal')]
    public function loadProduct($id)
    {
        $product = Product::findOrFail($id);
        $this->product_id = $product->id;
        $this->category_id = $product->category_id;
        $this->branch_id = $product->branch_id;
        $this->product_name = $product->product_name;
        $this->product_quantity = $product->product_quantity;
        $this->product_cost = $product->product_cost;
        $this->product_price = $product->product_price;

        Flux::modal('edit-product')->show();
    }

    public function update()
    {
        $validated = $this->validate(
            [
                'category_id' => 'required|exists:categories,id',
                'branch_id' => 'required|exists:branches,id',
                'product_name' => 'required|string|max:255',
                'product_quantity' => 'required|integer|min:0',
                'product_cost' => 'required|numeric|min:0',
                'product_price' => 'required|numeric|min:0',
            ],
            [
                'category_id.required' => 'يرجى اختيار التصنيف',
                'category_id.exists' => 'التصنيف المحدد غير موجود',
                'branch_id.required' => 'يرجى اختيار الفرع',
                'branch_id.exists' => 'الفرع المحدد غير موجود',
                'product_name.required' => 'اسم المنتج مطلوب',
                'product_name.string' => 'اسم المنتج يجب أن يكون نصاً',
                'product_name.max' => 'اسم المنتج لا يتجاوز 255 حرف',
                'product_quantity.required' => 'الكمية مطلوبة',
                'product_quantity.integer' => 'الكمية يجب أن تكون رقماً صحيحاً',
                'product_quantity.min' => 'الكمية لا يمكن أن تكون أقل من 0',
                'product_cost.required' => 'سعر التكلفة مطلوب',
                'product_cost.numeric' => 'سعر التكلفة يجب أن يكون رقماً',
                'product_cost.min' => 'سعر التكلفة لا يمكن أن يكون أقل من 0',
                'product_price.required' => 'سعر البيع مطلوب',
                'product_price.numeric' => 'سعر البيع يجب أن يكون رقماً',
                'product_price.min' => 'سعر البيع لا يمكن أن يكون أقل من 0',
            ],
        );


        $product = Product::findOrFail($this->product_id);
        $product->update($validated);

        Flux::modal('edit-product')->close();
        session()->flash('warning', 'تم تحديث بيانات المنتج بنجاح');

        return redirect()->route('products');
    }
};
?>

<div>
    <flux:modal name="edit-product" class="md:w-150">
        <form class="space-y-6" wire:submit.prevent="update">
            <!-- Header -->
            <div>
                <flux:heading size="lg">تعديل المنتج</flux:heading>
                <flux:text class="mt-2">تعديل المنتج مع جميع التفاصيل</flux:text>
            </div>

            <!-- Category Select -->
            <flux:select label="اختر تصنيف المنتج" searchable placeholder="اختر التصنيف" wire:model="category_id">
                <flux:select.option value="">اختر التصنيف</flux:select.option>
                @foreach ($this->categories as $category)
                    <flux:select.option value="{{ $category->id }}">
                        {{ $category->category_name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <!-- Product Name -->
            <flux:input label="اسم المنتج" type="text" placeholder="ادخل اسم المنتج" wire:model="product_name" />

            <!-- Quantity -->
            <flux:input label="الكمية" type="number" placeholder="ادخل الكمية" wire:model="product_quantity" />

            <!-- Cost -->
            <flux:input label="تكلفة المنتج" type="number" placeholder="تكلفة المنتج" wire:model="product_cost" />

            <!-- Price -->
            <flux:input label="سعر المنتج" type="number" placeholder="سعر المنتج" wire:model="product_price" />

            <!-- Branch Select -->
            <flux:select label="اختر الفرع" searchable placeholder="اختر الفرع" wire:model="branch_id">
                <flux:select.option value="">اختر الفرع</flux:select.option>
                @foreach ($this->branches as $branch)
                    <flux:select.option value="{{ $branch->id }}">
                        {{ $branch->branch_name }}
                    </flux:select.option>
                @endforeach
            </flux:select>

            <!-- Buttons -->
            <div class="grid grid-cols-3 items-center">
                <div>
                    <flux:modal.close>
                        <flux:button type="button" variant="danger">الغاء</flux:button>
                    </flux:modal.close>
                </div>
                <div></div>
                <div class="text-right">
                    <flux:button type="submit" variant="primary" color="green">تحديث المنتج</flux:button>
                </div>
            </div>
        </form>
    </flux:modal>
</div>
