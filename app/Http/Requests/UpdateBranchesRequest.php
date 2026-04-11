<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchesRequest extends FormRequest
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
            'branch_name' => 'required|string|max:255|unique:branches,branch_name',

            'branch_address' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_name.required' => 'اسم الفرع مطلوب',
            'branch_name.unique' => 'اسم الفرع مستخدم بالفعل',
        ];
    }
}
