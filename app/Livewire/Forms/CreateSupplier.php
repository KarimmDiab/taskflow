<?php

namespace App\Livewire\Forms;

use App\Models\Supplier;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateSupplier extends Form
{
    public ?Supplier $supplier = null;

    public string $supplier_name = '';

    public string $supplier_phone = '';

    public string $supplier_address = '';

    public function rules(): array
    {
        return [
            'supplier_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
            'supplier_phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/', // رقم موبايل مصري
                Rule::unique('suppliers', 'supplier_phone')->ignore($this->supplier?->id),
            ],
            'supplier_address' => [
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // supplier_name
            'supplier_name.required' => 'اسم المورد مطلوب.',
            'supplier_name.string' => 'اسم المورد يجب أن يكون نص.',
            'supplier_name.max' => 'اسم المورد لا يجب أن يزيد عن 255 حرف.',
            'supplier_name.regex' => 'اسم المورد يسمح بالحروف العربية والإنجليزية والأرقام والمسافات والشرطة فقط.',

            // supplier_phone
            'supplier_phone.required' => 'رقم الهاتف مطلوب.',
            'supplier_phone.string' => 'رقم الهاتف يجب أن يكون نص.',
            'supplier_phone.max' => 'رقم الهاتف لا يجب أن يزيد عن 11 رقم.',
            'supplier_phone.regex' => 'رقم الهاتف غير صحيح. يجب أن يبدأ ب 010 - 012 - 015 - 011 و يتكون من 11 رثم.',
            'supplier_phone.unique' => 'رقم الهاتف مستخدم بالفعل.',

            // supplier_address
            'supplier_address.string' => 'العنوان يجب أن يكون نص.',
            'supplier_address.max' => 'العنوان لا يجب أن يزيد عن 255 حرف.',
        ];
    }

    public function setSupplier(Supplier $supplier)
    {
        $this->supplier = $supplier;
        $this->supplier_name = $supplier->supplier_name;
        $this->supplier_phone = $supplier->supplier_phone;
        $this->supplier_address = $supplier->supplier_address;
    }

    public function store()
    {
        $data = $this->validate();
        Supplier::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->supplier->update([
            'supplier_name' => $this->supplier_name,
            'supplier_phone' => $this->supplier_phone,
            'supplier_address' => $this->supplier_address,
        ]);

    }
}
