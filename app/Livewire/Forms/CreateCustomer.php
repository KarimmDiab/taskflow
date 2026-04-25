<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateCustomer extends Form
{
    public ?Customer $customer = null;

    public string $customer_name = '';

    public string $contact_info = '';

    public function rules(): array
    {
        return [
            'customer_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
            'contact_info' => [
                'required',
                'string',
                'max:11',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/', // رقم موبايل مصري
                Rule::unique('customers', 'contact_info')->ignore($this->customer?->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // customer_name
            'customer_name.required' => 'اسم العميل مطلوب.',
            'customer_name.string' => 'اسم العميل يجب أن يكون نص.',
            'customer_name.max' => 'اسم العميل لا يجب أن يزيد عن 255 حرف.',
            'customer_name.regex' => 'اسم العميل يسمح بالحروف العربية والإنجليزية والأرقام والمسافات والشرطة فقط.',

            // contact_info
            'contact_info.required' => 'رقم الهاتف مطلوب.',
            'contact_info.string' => 'رقم الهاتف يجب أن يكون نص.',
            'contact_info.max' => 'رقم الهاتف لا يجب أن يزيد عن 11 رقم.',
            'contact_info.regex' => 'رقم الهاتف غير صحيح. يجب أن يبدأ بـ 010 أو 011 أو 012 أو 015 ويتكون من 11 رقم.',
            'contact_info.unique' => 'رقم الهاتف مستخدم بالفعل.',
        ];
    }

    public function setCustomer(Customer $customer)
    {
        $this->customer = $customer;
        $this->customer_name = $customer->customer_name;
        $this->contact_info = $customer->contact_info;
    }

    public function store()
    {
        $data = $this->validate();
        Customer::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->customer->update([
            'customer_name' => $this->customer_name,
            'contact_info' => $this->contact_info,
        ]);

    }
}
