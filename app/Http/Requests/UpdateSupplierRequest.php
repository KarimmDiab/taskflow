<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierRequest extends FormRequest
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
            'supplier_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9\s\-_]+$/u',
            ],

            'supplier_phone' => [
                'required',
                'string',
                'max:20',
                'regex:/^01[0-2,5]{1}[0-9]{8}$/',
            ],

            'supplier_address' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\p{Arabic}A-Za-z0-9\s\-_.,()]+$/u',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // name
            'supplier_name.required' => 'اسم المورد مطلوب',
            'supplier_name.string' => 'اسم المورد يجب أن يكون نص',
            'supplier_name.max' => 'اسم المورد لا يجب أن يزيد عن 255 حرف',
            'supplier_name.regex' => 'اسم المورد يسمح فقط بحروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافة',

            // phone
            'supplier_phone.required' => 'رقم الموبايل مطلوب',
            'supplier_phone.string' => 'رقم الموبايل يجب أن يكون نص',
            'supplier_phone.max' => 'رقم الموبايل لا يجب أن يزيد عن 20 رقم',
            'supplier_phone.regex' => 'رقم الموبايل غير صحيح، يجب أن يكون رقم يبدأ بـ 010 أو 011 أو 012 أو 015 ويتكون من 11 رقم',

            // address
            'supplier_address.string' => 'العنوان يجب أن يكون نص',
            'supplier_address.max' => 'العنوان لا يجب أن يزيد عن 500 حرف',
            'supplier_address.regex' => 'العنوان يحتوي على رموز غير مسموح بها',
        ];
    }
}
