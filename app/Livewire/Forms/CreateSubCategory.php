<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use App\Models\SubCategory;
use Livewire\Form;


class CreateSubCategory extends Form
{
    public ?SubCategory $subCategory = null;

    public string $sub_category_name = '';

    public string $sub_category_description = '';

    public $category_id  = null;


    public function rules(): array
    {
        return [
    
            'sub_category_name' => [
                'required',
                'string',
                'max:255',
                'unique:sub_categories,sub_category_name',
            ],
    
            'sub_category_description' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[^<>]*$/',
            ],
    
            'category_id' => [
                'required',
                'exists:categories,id',
            ],
    
        ];
    }
    
    public function messages(): array
    {
        return [
    
            // sub_category_name
            'sub_category_name.required' => 'اسم التصنيف الفرعي مطلوب.',
            'sub_category_name.string'   => 'اسم التصنيف الفرعي يجب أن يكون نص.',
            'sub_category_name.max'      => 'اسم التصنيف الفرعي لا يجب أن يتجاوز 255 حرف.',
            'sub_category_name.unique'   => 'اسم التصنيف الفرعي مستخدم بالفعل.',
    
            // sub_category_description
            'sub_category_description.string' => 'الوصف يجب أن يكون نص.',
            'sub_category_description.max'    => 'الوصف لا يجب أن يتجاوز 1000 حرف.',
            'sub_category_description.regex'  => 'الوصف لا يجب أن يحتوي على أكواد HTML أو رموز غير مسموحة.',
    
            // category_id
            'category_id.required' => 'يجب اختيار القسم الرئيسي.',
            'category_id.exists'   => 'القسم الرئيسي غير موجود.',
    
        ];
    }

    public function setSubCategory(SubCategory $subCategory)
    {
        $this->subCategory = $subCategory;
        $this->sub_category_name = $subCategory->sub_category_name;
        $this->sub_category_description = $subCategory->sub_category_description;
        $this->category_id = $subCategory->category_id;
    }

    public function store()
    {
        $data = $this->validate();
        SubCategory::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();

        $updateData = [
            'sub_category_name' => $this->sub_category_name,
            'sub_category_description' => $this->sub_category_description,
            'category_id' => $this->category_id,
        ];
        $this->subCategory->update($updateData);

    }
}
