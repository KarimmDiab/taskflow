<?php

namespace App\Livewire\Forms;

use Illuminate\Validation\Rule;
use Livewire\Form;
use Flux\Flux;
use App\Models\ExpensesItem;



class CreateExpensesItem extends Form
{
    public ?ExpensesItem $expenses_item = null;

    public string $expenses_name = '';


    public function rules(): array
    {
        return [
            'expenses_name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
                Rule::unique('expenses_items', 'expenses_name')->ignore($this->expenses_item?->id),
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'expenses_name.required' => 'اسم بند المصروفات مطلوب',
            'expenses_name.unique' => 'اسم بند المصروفات مستخدم بالفعل',
            'expenses_name.regex' => 'اسم بند المصروفات يقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',
        ];
    }

    public function setExpensesItem(ExpensesItem $expenses_item)
    {
        $this->expenses_item = $expenses_item;
        $this->expenses_name = $expenses_item->expenses_name;
    }

    public function store()
    {
        $data = $this->validate();

        ExpensesItem::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->expenses_item->update([
            'expenses_name' => $this->expenses_name,
        ]);
    }
}
