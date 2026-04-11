<?php

namespace App\Models;

use Database\Factories\CustomerSalesInvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerSalesInvoice extends Model
{
    /** @use HasFactory<CustomerSalesInvoiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_invoice_id',
        'customer_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }
}
