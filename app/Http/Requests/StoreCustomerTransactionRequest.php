<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerTransactionRequest extends FormRequest
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
            'sales_invoice_id' => 'required|integer|exists:sales_invoices,id',
            'customer_id' => 'required|integer|exists:customers,id',
            'paid_amount' => 'required|numeric|min:0.01',
            'notes' => [
                'nullable',
                'string',
                'max:1000',
                'regex:/^[\p{Arabic}A-Za-z0-9 _-]+$/u',
            ],
            'user_id' => 'required|integer|exists:users,id',
            'payment_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'sales_invoice_id.required' => 'رقم فاتورة البيع مطلوب',
            'sales_invoice_id.exists' => 'فاتورة البيع غير موجودة',

            'customer_id.required' => 'العميل مطلوب',
            'customer_id.exists' => 'العميل غير موجود',

            'paid_amount.required' => 'قيمة الدفع مطلوبة',
            'paid_amount.numeric' => 'قيمة الدفع لازم تكون رقم',
            'paid_amount.min' => 'قيمة الدفع لازم تكون أكبر من صفر',

            'notes.string' => 'الملاحظات لازم تكون نص',
            'notes.max' => 'الملاحظات لا يجب أن تتجاوز 1000 حرف',
            'notes.regex' => 'الملاحظات تقبل حروف عربي أو إنجليزي أو أرقام أو _ أو - أو مسافات فقط',

            'user_id.required' => 'المستخدم مطلوب',
            'user_id.exists' => 'المستخدم غير موجود',

            'payment_date.required' => 'تاريخ الدفع مطلوب',
            'payment_date.date' => 'تاريخ الدفع غير صحيح',
        ];
    }
}
