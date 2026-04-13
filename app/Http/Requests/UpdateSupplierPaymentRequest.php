<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierPaymentRequest extends FormRequest
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
            'payment_date' => [
                'required',
                'date',
                'regex:/^\d{2}-\d{2}-\d{4}$/',
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[\p{Arabic}A-Za-z0-9\s\-_.,()]+$/u',
            ],

            'purchase_invoice_id' => [
                'required',
                'integer',
                'exists:purchase_invoices,id',
            ],

            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],

            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // date
            'payment_date.required' => 'تاريخ الدفع مطلوب',
            'payment_date.date' => 'صيغة التاريخ غير صحيحة',
            'payment_date.regex' => 'التاريخ يجب أن يكون بصيغة DD-MM-YYYY',

            // amount
            'paid_amount.required' => 'المبلغ المدفوع مطلوب',
            'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقم',
            'paid_amount.min' => 'المبلغ المدفوع لا يمكن أن يكون أقل من 0',
            'paid_amount.regex' => 'المبلغ المدفوع يجب أن يكون رقم صحيح أو عشري بحد أقصى منزلتين',

            // note
            'note.string' => 'الملاحظات يجب أن تكون نص',
            'note.max' => 'الملاحظات لا يجب أن تزيد عن 1000 حرف',
            'note.regex' => 'الملاحظات تحتوي على رموز غير مسموح بها',

            // invoice
            'purchase_invoice_id.required' => 'فاتورة الشراء مطلوبة',
            'purchase_invoice_id.integer' => 'فاتورة الشراء غير صحيحة',
            'purchase_invoice_id.exists' => 'فاتورة الشراء غير موجودة',

            // supplier
            'supplier_id.required' => 'المورد مطلوب',
            'supplier_id.integer' => 'المورد غير صحيح',
            'supplier_id.exists' => 'المورد غير موجود',

            // user
            'user_id.required' => 'المستخدم مطلوب',
            'user_id.integer' => 'المستخدم غير صحيح',
            'user_id.exists' => 'المستخدم غير موجود',
        ];
    }
}
