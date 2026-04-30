<?php

use Livewire\Component;
use App\Livewire\Forms\ProductForm;
use Livewire\Attributes\On;
use App\Models\Product;
use App\Models\Branches;

use App\Models\Category;

new class extends Component {
    public ProductForm $form;

    public function getCategoriesProperty()
    {
        return Category::select('id', 'category_name')->get();
    }

    public function getBranchesProperty()
    {
        return Branches::select('id', 'branch_name')->get();
    }

    #[On('editProduct')]
    public function editProduct($id)
    {
        //return dd($id);
        $product = Product::findOrFail($id);
        $this->form->setProduct($product);
        Flux::modal('edit-product')->show();
    }

    public function updateProduct()
    {
        $this->form->update();
        Flux::modal('edit-product')->close();
        session()->flash('warning', 'تم تحديث بيانات المنتج بنجاح');
        $this->redirectRoute('products', navigate: true);
    }

    public function confirmDelete($id)
    {
        //return dd($id);
        $product = Product::findOrFail($id);
        $this->form->setProduct($product);
        $this->form->product->delete();
        session()->flash('success', 'تم حذف بيانات المنتج بنجاح');
        $this->redirectRoute('products', navigate: true);
    }

};
?>

<div>


    <div>

        <flux:modal name="edit-product" class="md:w-150">

            <form class="space-y-6" wire:submit.prevent="updateProduct">

                <!-- 🔹 Header -->
                <div>
                    <flux:heading size="lg">تعديل المنتج </flux:heading>
                    <flux:text class="mt-2">تعديل المنتج مع جميع التفاصيل</flux:text>
                </div>

                <!-- 🔥 Select (Search شغال + مربوط) -->
                <flux:select label="اختر تصنيف المنتج" searchable placeholder="اختر التصنيف" wire:model="form.category_id"
                    wire:dirty.class="rind-1 ring-yellow-400">
                    <flux:select.option value=""> اختر التصنيف </flux:select.option>
                    @foreach ($this->categories as $category)
                        <flux:select.option value="{{ $category->id }}"> {{ $category->category_name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <!-- 🔹 اسم المنتج -->
                <flux:input label="اسم المنتج" type="text" placeholder="ادخل اسم المنتج"
                    wire:model="form.product_name" wire:dirty.class="rind-1 ring-yellow-400" />

                <!-- 🔹 الكمية -->
                <flux:input label="الكمية" type="number" placeholder="ادخل الكمية" wire:model="form.product_quantity"
                    wire:dirty.class="rind-1 ring-yellow-400" />

                <!-- 🔹 سعر التكلفة -->
                <flux:input label="تكلفة المنتج" type="number" placeholder="تكلفة المنتج"
                    wire:model="form.product_cost" wire:dirty.class="rind-1 ring-yellow-400" />

                <!-- 🔹 السعر -->
                <flux:input label="سعر المنتج" type="number" placeholder="سعر المنتج" wire:model="form.product_price"
                    wire:dirty.class="rind-1 ring-yellow-400" />


                <!-- 🔹 الفرع -->
                <flux:select label="اختر الفرع" searchable placeholder="اختر الفرع" wire:model="form.branch_id"
                    wire:dirty.class="rind-1 ring-yellow-400">
                    <flux:select.option value=""> اختر الفرع </flux:select.option>
                    @foreach ($this->branches as $branch)
                        <flux:select.option value="{{ $branch->id }}"> {{ $branch->branch_name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

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
                            تحديث المنتج
                        </flux:button>
                    </div>

                </div>

            </form>

        </flux:modal>

    </div>
