<?php

namespace App\Observers;

use App\Models\SalesInvoice;
use App\Services\NotificationService;

class SalesInvoiceObserver
{
    public function created(SalesInvoice $salesInvoice): void
    {
        if (! str_starts_with((string) $salesInvoice->invoice_number, 'POS-')) {
            return;
        }

        app(NotificationService::class)->create(
            title: 'New Retail Sale',
            message: "New retail sale invoice #{$salesInvoice->invoice_number} created with total ".number_format((float) $salesInvoice->total_amount, 2),
            type: 'retail_sale_created',
            module: 'retail_sales',
            referenceId: $salesInvoice->id,
            data: [
                'invoice_number' => $salesInvoice->invoice_number,
                'total_amount' => (float) $salesInvoice->total_amount,
                'net_total' => (float) $salesInvoice->net_total,
            ],
        );
    }
}
