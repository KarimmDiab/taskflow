<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateCategory extends Form
{
    public ?Category $category = null;

    public string $category_name = '';

    public string $category_description = '';

    public function rules(): array
    {
        return [
            'category_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('categories', 'category_name')->ignore($this->category?->id),
            ],

            'category_description' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'اسم التصنيف مطلوب',
            'category_name.string' => 'اسم التصنيف يجب أن يكون نص',
            'category_name.max' => 'اسم التصنيف لا يجب أن يتجاوز 255 حرف',
            'category_name.unique' => 'اسم التصنيف مستخدم بالفعل',
            'category_name.regex' => 'اسم التصنيف يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',

            'category_description.string' => 'الوصف يجب أن يكون نص',
            'category_description.max' => 'الوصف لا يجب أن يتجاوز 500 حرف',
            'category_description.regex' => 'الوصف يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
        ];
    }

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->category_name = $category->category_name;
        $this->category_description = $category->category_description ?? '';
    }

    public function store()
    {
        $data = $this->validate();

        Category::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->category->update([
            'category_name' => $this->category_name,
            'category_description' => $this->category_description,
        ]);
    }
}
