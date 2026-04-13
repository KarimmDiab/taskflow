<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreExpensesDetailRequest extends FormRequest
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
            'expenses_date' => [
                'required',
                'date',
                'regex:/^\d{2}-\d{2}-\d{4}$/',
            ],
    
            'expenses_cost' => [
                'required',
                'numeric',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
    
            'expenses_note' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[\p{Arabic}A-Za-z0-9\s\-_.,()]+$/u',
            ],
    
            'expenses_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
    
            'expenses_item_id' => [
                'required',
                'integer',
                'exists:expenses_items,id',
            ],
        ];
    }
    
    public function messages(): array
    {
        return [
            // expenses_date
            'expenses_date.required' => 'تاريخ المصروف مطلوب',
            'expenses_date.date' => 'صيغة التاريخ غير صحيحة',
            'expenses_date.regex' => 'يجب أن يكون التاريخ بصيغة DD-MM-YYYY',
    
            // expenses_cost
            'expenses_cost.required' => 'قيمة المصروف مطلوبة',
            'expenses_cost.numeric' => 'قيمة المصروف يجب أن تكون رقم',
            'expenses_cost.regex' => 'قيمة المصروف يجب أن تكون رقم صحيح أو عشري (بحد أقصى منزلتين)',
    
            // expenses_note
            'expenses_note.string' => 'الملاحظات يجب أن تكون نص',
            'expenses_note.max' => 'الملاحظات لا يجب أن تزيد عن 1000 حرف',
            'expenses_note.regex' => 'الملاحظات تحتوي على رموز غير مسموح بها',
    
            // expenses_image
            'expenses_image.image' => 'الملف المرفوع يجب أن يكون صورة',
            'expenses_image.mimes' => 'الصورة يجب أن تكون jpg أو jpeg أو png أو webp',
            'expenses_image.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجا',
    
            // expenses_item_id
            'expenses_item_id.required' => 'نوع المصروف مطلوب',
            'expenses_item_id.integer' => 'نوع المصروف غير صحيح',
            'expenses_item_id.exists' => 'نوع المصروف غير موجود في النظام',
        ];
    }
}
