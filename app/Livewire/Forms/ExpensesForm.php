<?php

namespace App\Livewire\Forms;

use App\Models\ExpensesDetail;
use Livewire\Form;

class ExpensesForm extends Form
{
    public ?ExpensesDetail $expensesDetail = null;

    public string $expenses_date = '';

    public $expenses_cost = null;

    public string $expenses_note = '';

    public string $expenses_image = '';

    public $expenses_item_id = null;


    public function rules(): array
    {
        return [
            'expenses_date' => [
                'required',
                'date',
            ],

            'expenses_cost' => [
                'required',
                'numeric',
                'min:0',
            ],

            'expenses_note' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[^<>]*$/', // يمنع HTML/Script بسيط
            ],

            'expenses_image' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\/\.\-_]+$/', // يمنع injection في path
            ],

            'expenses_item_id' => [
                'required',
                'exists:expenses_items,id',
            ]
        ];
    }

    public function messages(): array
    {
        return [

            // expenses_date
            'expenses_date.required' => 'تاريخ المصروف مطلوب.',
            'expenses_date.date' => 'يجب إدخال تاريخ صحيح.',

            // expenses_cost
            'expenses_cost.required' => 'قيمة المصروف مطلوبة.',
            'expenses_cost.numeric' => 'قيمة المصروف يجب أن تكون رقم.',
            'expenses_cost.min' => 'قيمة المصروف لا يمكن أن تكون أقل من 0.',

            // expenses_note
            'expenses_note.string' => 'الملاحظات يجب أن تكون نص.',
            'expenses_note.max' => 'الملاحظات لا يجب أن تتجاوز 500 حرف.',
            'expenses_note.regex' => 'الملاحظات لا يجب أن تحتوي على رموز HTML مثل < أو >.',

            // expenses_image
            'expenses_image.string' => 'مسار الصورة يجب أن يكون نص.',
            'expenses_image.max' => 'مسار الصورة لا يجب أن يتجاوز 255 حرف.',
            'expenses_image.regex' => 'مسار الصورة يحتوي على رموز غير مسموح بها.',

            // expenses_item_id
            'expenses_item_id.required' => 'يجب اختيار بند المصروف.',
            'expenses_item_id.exists' => 'بند المصروف غير موجود.',

        ];
    }

    public function setExpensesDetail(ExpensesDetail $expensesDetail)
    {
        $this->expensesDetail = $expensesDetail;
        $this->expenses_date = $expensesDetail->expenses_date;
        $this->expenses_cost = $expensesDetail->expenses_cost;
        $this->expenses_note = $expensesDetail->expenses_note;
        $this->expenses_image = $expensesDetail->expenses_image;
        $this->expenses_item_id = $expensesDetail->expenses_item_id;
    }

    public function store()
    {
        $data = $this->validate();
        $data['user_id'] = auth()->id();
        ExpensesDetail::create($data);
        $this->reset();
    }

    public function update()
    {
        $this->validate();
        $this->expensesDetail->update([
            'expenses_date' => $this->expenses_date,
            'expenses_cost' => $this->expenses_cost,
            'expenses_note' => $this->expenses_note,
            'expenses_image' => $this->expenses_image,
            'expenses_item_id' => $this->expenses_item_id,
        ]);

    }
}
