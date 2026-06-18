<?php

namespace App\Services;

use App\Models\SalesActivityLog;
use App\Models\SalesInvoice;
use App\Models\SalesReturn;

class SalesActivityLogger
{
    public function invoice(SalesInvoice $invoice, string $action, ?string $description = null, array $properties = []): void
    {
        SalesActivityLog::create([
            'sales_invoice_id' => $invoice->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'properties' => $properties ?: null,
        ]);
    }

    public function salesReturn(SalesReturn $return, string $action, ?string $description = null, array $properties = []): void
    {
        SalesActivityLog::create([
            'sales_invoice_id' => $return->sales_invoice_id,
            'sales_return_id' => $return->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'properties' => $properties ?: null,
        ]);
    }
}
