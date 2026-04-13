<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\CustomerSalesInvoice;
use App\Models\SalesInvoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomerSalesInvoice>
 */
class CustomerSalesInvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $used = [];
    
        do {
            $salesInvoiceId = SalesInvoice::inRandomOrder()->value('id');
            $customerId = Customer::inRandomOrder()->value('id');
    
            $key = $salesInvoiceId . '-' . $customerId;
    
        } while (in_array($key, $used));
    
        $used[] = $key;
    
        return [
            'sales_invoice_id' => $salesInvoiceId,
            'customer_id' => $customerId,
        ];
    }
}
