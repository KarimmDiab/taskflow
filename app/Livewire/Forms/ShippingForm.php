<?php

namespace App\Livewire\Forms;

use App\Models\Shipping;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ShippingForm extends Form
{
    public ?Shipping $shipping = null;

    public string $city_name = '';

    public ?int $shipping_cost = null;     // تكلفة الشحن (رقم)

    public ?string $estimated_days = null; // الوقت المتوقع (نص، مثل "2-3 أيام")

    public bool $is_active = true;

    public function rules(): array
    {
        return [
            'city_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('shippings', 'city_name')->ignore($this->shipping?->id)->whereNull('deleted_at'),
            ],
            'shipping_cost' => 'nullable|integer|min:0',
            'estimated_days' => 'nullable|string|max:100',  // نص، بحد أقصى 100 حرف
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'city_name.required' => 'اسم المحافظة مطلوب',
            'city_name.unique' => 'اسم المحافظة مستخدم بالفعل',
            'city_name.regex' => 'اسم المحافظة يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
            'shipping_cost.integer' => 'تكلفة الشحن يجب أن تكون رقماً صحيحاً',
            'shipping_cost.min' => 'تكلفة الشحن يجب أن تكون 0 أو أكثر',
            'estimated_days.string' => 'الوقت المتوقع يجب أن يكون نصاً',
            'estimated_days.max' => 'الوقت المتوقع لا يتجاوز 100 حرف',
        ];
    }

    public function setShipping(Shipping $shipping): void
    {
        $this->shipping = $shipping;
        $this->city_name = $shipping->city_name;
        $this->shipping_cost = $shipping->shipping_cost;
        $this->estimated_days = $shipping->estimated_days;
        $this->is_active = (bool) $shipping->is_active;
    }

    public function store(): void
    {
        $data = $this->validate();
        if (is_null($data['shipping_cost'])) {
            $data['shipping_cost'] = 0;
        }
        Shipping::create($data);
        $this->reset();
    }

    public function update(): void
    {
        $data = $this->validate();
        if (is_null($data['shipping_cost'])) {
            $data['shipping_cost'] = 0;
        }
        $this->shipping->update($data);
    }
}
