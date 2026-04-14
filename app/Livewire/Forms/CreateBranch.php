<?php

namespace App\Livewire\Forms;

use App\Models\Branches;
use Livewire\Form;

class CreateBranch extends Form
{
    public string $branch_name = '';

    public string $branch_address = '';

    public function rules(): array
    {
        return [
            'branch_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                'unique:branches,branch_name',
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
            'branch_name.unique' => 'اسم الفرع مستخدم بالفعل',
            'branch_name.regex' => 'اسم الفرع يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',

            'branch_address.regex' => 'العنوان يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
        ];
    }

    public function store()
    {
        $data = $this->validate();

        Branches::create($data);
        $this->reset();
    }
}
