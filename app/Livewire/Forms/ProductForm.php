<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Livewire\Form;

class ProductForm extends Form
{
    public ?Product $product = null;

    public string $product_name = '';

    public ?int $product_quantity = null;

    public ?float $product_cost = null;

    public ?float $product_price = null;

    public ?int $category_id = null;

    public ?int $branch_id = null;

    public function rules(): array
    {
        return [
            'product_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],

            'product_quantity' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'product_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'product_price' => [
                'required',
                'numeric',
                'min:0',
                'gte:product_cost', // السعر لازم يكون >= التكلفة
            ],

            'category_id' => [
                'required',
                'exists:categories,id',
            ],

            'branch_id' => [
                'required',
                'exists:branches,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // product_name
            'product_name.required' => 'اسم المنتج مطلوب.',
            'product_name.string' => 'اسم المنتج يجب أن يكون نص.',
            'product_name.max' => 'اسم المنتج لا يجب أن يزيد عن 255 حرف.',
            'product_name.regex' => 'اسم المنتج يحتوي على رموز غير مسموح بها.',

            // product_quantity
            'product_quantity.integer' => 'الكمية يجب أن تكون رقم صحيح.',
            'product_quantity.min' => 'الكمية لا يمكن أن تكون أقل من 0.',

            // product_cost
            'product_cost.required' => 'تكلفة المنتج مطلوبة.',
            'product_cost.numeric' => 'التكلفة يجب أن تكون رقم.',
            'product_cost.min' => 'التكلفة لا يمكن أن تكون أقل من 0.',

            // product_price
            'product_price.required' => 'سعر البيع مطلوب.',
            'product_price.numeric' => 'سعر البيع يجب أن يكون رقم.',
            'product_price.min' => 'سعر البيع لا يمكن أن يكون أقل من 0.',
            'product_price.gte' => 'سعر البيع يجب أن يكون أكبر من أو يساوي التكلفة.',

            // category_id
            'category_id.required' => 'يجب اختيار التصنيف.',
            'category_id.exists' => 'التصنيف غير موجود.',

            // branch_id
            'branch_id.required' => 'يجب اختيار الفرع.',
            'branch_id.exists' => 'الفرع غير موجود.',
        ];
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        $this->product_name = $product->product_name;
        $this->product_quantity = $product->product_quantity;
        $this->product_cost = $product->product_cost;
        $this->product_price = $product->product_price;
        $this->category_id = $product->category_id;
        $this->branch_id = $product->branch_id;
    }

    public function store()
    {
        $data = $this->validate();
        Product::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->product->update([
            'product_name' => $this->product_name,
            'product_quantity' => $this->product_quantity,
            'product_cost' => $this->product_cost,
            'product_price' => $this->product_price,
            'category_id' => $this->category_id,
            'branch_id' => $this->branch_id,
        ]);

    }
}
