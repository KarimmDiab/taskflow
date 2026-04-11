<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_name' => 'required|string|max:255',
            'contact_info' => 'nullable|regex:/^01[0-2,5]{1}[0-9]{8}$/',
            ];
    }

    public function messages(): array
    {
        return [
            'customer_name.required' => 'اسم العميل مطلوب',
            'customer_name.string' => 'اسم العميل لازم يكون نص',
            'customer_name.max' => 'اسم العميل لا يجب أن يتجاوز 255 حرف',
            'customer_name.unique' => 'اسم العميل مستخدم بالفعل',
    
            'contact_info.regex' => 'رقم الموبايل غير صحيح (يجب أن يكون رقم مصري صحيح مثل 010xxxxxxxx)',
        ];
    }
}
