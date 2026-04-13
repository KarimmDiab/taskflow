<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreSalesInvoiceRequest extends FormRequest
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
            'total_amount' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'deduction' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'net_total' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
                'lte:net_total',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'remaining_amount' => [
                'nullable',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'payment_method' => [
                'required',
                'in:cash,visa,credit,bank_transfer',
            ],

            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // total
            'total_amount.required' => 'إجمالي الفاتورة مطلوب',
            'total_amount.numeric' => 'إجمالي الفاتورة يجب أن يكون رقم',
            'total_amount.min' => 'إجمالي الفاتورة لا يمكن أن يكون أقل من 0',
            'total_amount.regex' => 'إجمالي الفاتورة يجب أن يكون رقم صحيح أو عشري',

            // deduction
            'deduction.numeric' => 'الخصم يجب أن يكون رقم',
            'deduction.min' => 'الخصم لا يمكن أن يكون أقل من 0',
            'deduction.regex' => 'الخصم يجب أن يكون رقم صحيح أو عشري',

            // net total
            'net_total.required' => 'الصافي مطلوب',
            'net_total.numeric' => 'الصافي يجب أن يكون رقم',
            'net_total.min' => 'الصافي لا يمكن أن يكون أقل من 0',
            'net_total.regex' => 'الصافي يجب أن يكون رقم صحيح أو عشري',

            // paid
            'paid_amount.required' => 'المبلغ المدفوع مطلوب',
            'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقم',
            'paid_amount.min' => 'المبلغ المدفوع لا يمكن أن يكون أقل من 0',
            'paid_amount.lte' => 'المبلغ المدفوع لا يمكن أن يتجاوز صافي الفاتورة',
            'paid_amount.regex' => 'المبلغ المدفوع يجب أن يكون رقم صحيح أو عشري',

            // remaining
            'remaining_amount.numeric' => 'المبلغ المتبقي يجب أن يكون رقم',
            'remaining_amount.min' => 'المبلغ المتبقي لا يمكن أن يكون أقل من 0',
            'remaining_amount.regex' => 'المبلغ المتبقي يجب أن يكون رقم صحيح أو عشري',

            // payment method
            'payment_method.required' => 'طريقة الدفع مطلوبة',
            'payment_method.in' => 'طريقة الدفع يجب أن تكون: cash أو visa أو credit أو bank_transfer',

            // user
            'user_id.required' => 'المستخدم مطلوب',
            'user_id.integer' => 'المستخدم غير صحيح',
            'user_id.exists' => 'المستخدم غير موجود',

            // branch
            'branch_id.required' => 'الفرع مطلوب',
            'branch_id.integer' => 'الفرع غير صحيح',
            'branch_id.exists' => 'الفرع غير موجود',
        ];
    }
}
