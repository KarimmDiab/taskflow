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
        return [
            'sales_invoice_id' => SalesInvoice::inRandomOrder()->value('id'),
            'customer_id' => Customer::inRandomOrder()->value('id'),
        ];
    }
}
