<?php

namespace App\Livewire\Forms;

use App\Models\Size;
use Illuminate\Validation\Rule;
use Livewire\Form;

class SizeForm extends Form
{
    public ?Size $size = null;

    public string $size_name = '';

    public ?int $sort_order = null;  // nullable, will default to 0 or last+1 in controller

    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'size_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('sizes', 'size_name')->ignore($this->size?->id)->whereNull('deleted_at'),
            ],
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'size_name.required' => 'اسم المقاس مطلوب',
            'size_name.unique' => 'اسم المقاس مستخدم بالفعل',
            'size_name.regex' => 'اسم المقاس يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
            'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقماً صحيحاً',
            'sort_order.min' => 'ترتيب العرض يجب أن يكون 0 أو أكبر',
        ];
    }

    public function setSize(Size $size): void
    {
        $this->size = $size;
        $this->size_name = $size->size_name;
        $this->sort_order = $size->sort_order;
        $this->is_active = (bool) $size->is_active;
    }

    public function store(): void
    {
        $data = $this->validate();
        Size::create($data);
        $this->reset();
    }

    public function update(): void
    {
        $data = $this->validate();
        $this->size->update($data);
    }
}
