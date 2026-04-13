<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBranchesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true ;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'branch_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                'unique:branches,branch_name'
            ],
            'branch_address' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'branch_name.required' => 'اسم الفرع مطلوب',
            'branch_name.unique'   => 'اسم الفرع مستخدم بالفعل',
            'branch_name.regex'    => 'اسم الفرع يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
    
            'branch_address.regex' => 'العنوان يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
        ];
    }
}
