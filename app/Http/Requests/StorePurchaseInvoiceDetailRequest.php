<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseInvoiceDetailRequest extends FormRequest
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
            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'purchase_invoice_id' => [
                'required',
                'integer',
                'exists:purchase_invoices,id',
            ],

            'product_quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'cost_per_piece' => [
                'required',
                'numeric',
                'min:0',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            // product
            'product_id.required' => 'المنتج مطلوب',
            'product_id.integer' => 'المنتج غير صحيح',
            'product_id.exists' => 'المنتج غير موجود',

            // invoice
            'purchase_invoice_id.required' => 'فاتورة الشراء مطلوبة',
            'purchase_invoice_id.integer' => 'فاتورة الشراء غير صحيحة',
            'purchase_invoice_id.exists' => 'فاتورة الشراء غير موجودة',

            // quantity
            'product_quantity.required' => 'كمية المنتج مطلوبة',
            'product_quantity.integer' => 'الكمية يجب أن تكون رقم صحيح',
            'product_quantity.min' => 'الكمية يجب أن تكون على الأقل 1',

            // cost
            'cost_per_piece.required' => 'سعر القطعة مطلوب',
            'cost_per_piece.numeric' => 'سعر القطعة يجب أن يكون رقم',
            'cost_per_piece.min' => 'سعر القطعة لا يمكن أن يكون أقل من 0',
            'cost_per_piece.regex' => 'سعر القطعة يجب أن يكون رقم صحيح أو عشري بحد أقصى منزلتين',
        ];
    }
}
