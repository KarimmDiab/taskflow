<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSalesReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('sales.return.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'return_type' => ['required', 'in:full,partial,exchange'],
            'refund_method' => ['required', 'in:cash_refund,store_credit,product_exchange,no_refund'],
            'reason' => ['required', 'string', 'min:3'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.sales_invoice_detail_id' => ['required', 'integer', 'exists:sales_invoice_details,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
