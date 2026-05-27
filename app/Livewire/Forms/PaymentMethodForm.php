<?php

namespace App\Livewire\Forms;

use App\Models\PaymentMethod;
use Illuminate\Validation\Rule;
use Livewire\Form;

class PaymentMethodForm extends Form
{
    public ?PaymentMethod $payment_method = null;

    public string $payment_method_name = '';

    public bool $is_active = true;  // القيمة الافتراضية true (نشط)

    public function rules(): array
    {
        return [
            'payment_method_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('payment_methods', 'payment_method_name')
                    ->ignore($this->payment_method?->id)
                    ->whereNull('deleted_at'),
            ],
            'is_active' => 'boolean',  // التحقق من أن القيمة هي true/false فقط
        ];
    }

    public function messages(): array
    {
        return [
            'payment_method_name.required' => 'اسم طريقة الدفع مطلوب',
            'payment_method_name.unique' => 'اسم طريقة الدفع مستخدم بالفعل',
            'payment_method_name.regex' => 'اسم طريقة الدفع يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
            'is_active.boolean' => 'حقل الحالة يجب أن يكون نشط أو غير نشط',
        ];
    }

    public function setPaymentMethod(PaymentMethod $payment_method)
    {
        $this->payment_method = $payment_method;
        $this->payment_method_name = $payment_method->payment_method_name;
        $this->is_active = (bool) $payment_method->is_active;  // تحويل إلى boolean صريح
    }

    public function store()
    {
        $data = $this->validate();
        PaymentMethod::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->payment_method->update([
            'payment_method_name' => $this->payment_method_name,
            'is_active' => $this->is_active,
        ]);
    }
}
