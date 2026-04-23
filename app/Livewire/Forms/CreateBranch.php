<?php

namespace App\Livewire\Forms;

use App\Models\Branches;
use Flux\Flux;
use Illuminate\Validation\Rule;
use Livewire\Form;

class CreateBranch extends Form
{
    public ?Branches $branch = null;
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
                Rule::unique('branches', 'branch_name')->ignore($this->branch?->id),
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


    public function setBranch(Branches $branch)
    {
        $this->branch = $branch;
        $this->branch_name = $branch->branch_name;
        $this->branch_address = $branch->branch_address ?? '';
    }


    public function store()
    {
        $data = $this->validate();

        Branches::create($data);
        $this->reset();
    }

        public function update()
    {
        $this->validate();
        $this->branch->update([
            'branch_name' => $this->branch_name,
            'branch_address' => $this->branch_address,
        ]);
    }
}
