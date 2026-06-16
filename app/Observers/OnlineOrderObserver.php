<?php

namespace App\Observers;

use App\Models\OnlineOrder;
use App\Services\NotificationService;

class OnlineOrderObserver
{
    public function created(OnlineOrder $onlineOrder): void
    {
        app(NotificationService::class)->create(
            title: 'New Online Order',
            message: "New online order received from {$onlineOrder->customer_name}",
            type: 'online_order_created',
            module: 'online_orders',
            referenceId: $onlineOrder->id,
            data: [
                'customer_name' => $onlineOrder->customer_name,
                'customer_phone' => $onlineOrder->customer_phone,
                'sales_invoice_id' => $onlineOrder->sales_invoice_id,
            ],
        );
    }
}
