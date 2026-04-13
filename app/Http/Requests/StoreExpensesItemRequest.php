<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpensesItemRequest extends FormRequest
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
            'expenses_name' => [
                'required',
                'string',
                'max:200',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],        ];
    }

    public function messages(): array
    {
        return [
            'expenses_name.required' => 'بند المصاريف مطلوب',
            'expenses_name.string' => 'بند المصاريف يجب ان يكون نص',
            'expenses_name.max' => 'بند المصاريف يجب الا يتعدي 200 حرف',
            'expenses_name.regex'    => 'اسم المصروف يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',

        ];
    }
}
