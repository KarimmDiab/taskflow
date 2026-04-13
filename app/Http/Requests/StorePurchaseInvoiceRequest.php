<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseInvoiceRequest extends FormRequest
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
            'purchase_invoice_date' => [
                'required',
                'date',
                'regex:/^\d{2}-\d{2}-\d{4}$/',
            ],

            'total_amount' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],

            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
                'lte:total_amount', // المدفوع لا يتجاوز الإجمالي
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

            'product_image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // date
            'purchase_invoice_date.required' => 'تاريخ فاتورة الشراء مطلوب',
            'purchase_invoice_date.date' => 'صيغة التاريخ غير صحيحة',
            'purchase_invoice_date.regex' => 'التاريخ يجب أن يكون بصيغة YYYY-MM-DD',

            // total
            'total_amount.required' => 'إجمالي الفاتورة مطلوب',
            'total_amount.numeric' => 'إجمالي الفاتورة يجب أن يكون رقم',
            'total_amount.min' => 'إجمالي الفاتورة لا يمكن أن يكون أقل من 0',
            'total_amount.regex' => 'إجمالي الفاتورة يجب أن يكون رقم صحيح أو عشري',

            // paid
            'paid_amount.required' => 'المبلغ المدفوع مطلوب',
            'paid_amount.numeric' => 'المبلغ المدفوع يجب أن يكون رقم',
            'paid_amount.min' => 'المبلغ المدفوع لا يمكن أن يكون أقل من 0',
            'paid_amount.lte' => 'المبلغ المدفوع لا يمكن أن يتجاوز إجمالي الفاتورة',
            'paid_amount.regex' => 'المبلغ المدفوع يجب أن يكون رقم صحيح أو عشري',

            // remaining
            'remaining_amount.numeric' => 'المبلغ المتبقي يجب أن يكون رقم',
            'remaining_amount.min' => 'المبلغ المتبقي لا يمكن أن يكون أقل من 0',
            'remaining_amount.regex' => 'المبلغ المتبقي يجب أن يكون رقم صحيح أو عشري',

            // payment method
            'payment_method.required' => 'طريقة الدفع مطلوبة',
            'payment_method.in' => 'طريقة الدفع يجب أن تكون: cash أو visa أو credit أو bank_transfer',

            // image
            'product_image.image' => 'المرفق يجب أن يكون صورة',
            'product_image.mimes' => 'الصورة يجب أن تكون jpg أو jpeg أو png أو webp',
            'product_image.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجا',

            // branch
            'branch_id.required' => 'الفرع مطلوب',
            'branch_id.integer' => 'الفرع غير صحيح',
            'branch_id.exists' => 'الفرع غير موجود',

            // supplier
            'supplier_id.required' => 'المورد مطلوب',
            'supplier_id.integer' => 'المورد غير صحيح',
            'supplier_id.exists' => 'المورد غير موجود',
        ];
    }
}
