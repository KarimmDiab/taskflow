<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'product_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9\s\-_()]+$/u',
            ],

            'product_quantity' => [
                'required',
                'integer',
                'min:0',
            ],

            'product_price' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/', // decimal up to 2 digits
            ],

            'category_id' => [
                'required',
                'integer',
                'exists:categories,id',
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // product_name
            'product_name.required' => 'اسم المنتج مطلوب',
            'product_name.string' => 'اسم المنتج يجب أن يكون نص',
            'product_name.max' => 'اسم المنتج لا يجب أن يزيد عن 255 حرف',
            'product_name.regex' => 'اسم المنتج يحتوي على رموز غير مسموح بها',

            // product_quantity
            'product_quantity.required' => 'كمية المنتج مطلوبة',
            'product_quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'product_quantity.min' => 'الكمية لا يمكن أن تكون أقل من 0',

            // product_price
            'product_price.required' => 'سعر المنتج مطلوب',
            'product_price.numeric' => 'سعر المنتج يجب أن يكون رقم',
            'product_price.min' => 'سعر المنتج لا يمكن أن يكون أقل من 0',
            'product_price.regex' => 'سعر المنتج يجب أن يكون رقم صحيح أو عشري بحد أقصى منزلتين',

            // category_id
            'category_id.required' => 'التصنيف مطلوب',
            'category_id.integer' => 'التصنيف غير صحيح',
            'category_id.exists' => 'التصنيف غير موجود',

            // branch_id
            'branch_id.required' => 'الفرع مطلوب',
            'branch_id.integer' => 'الفرع غير صحيح',
            'branch_id.exists' => 'الفرع غير موجود',
        ];
    }
}
