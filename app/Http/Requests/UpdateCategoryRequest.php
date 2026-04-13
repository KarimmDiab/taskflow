<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
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
            'category_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                'unique:categories,category_name'
            ],
            'category_description' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_name.required' => 'اسم التصنيف مطلوب',
            'category_name.unique'   => 'اسم التصنيف مستخدم بالفعل',
            'category_name.regex'    => 'اسم التصنيف يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
    
            'category_description.regex' => 'الوصف يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
        ];
    }
}
