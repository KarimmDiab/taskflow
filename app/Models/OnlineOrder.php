<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OnlineOrder extends Model
{
    /** @use HasFactory<\Database\Factories\OnlineOrdersFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_invoice_id',
        'shipping_id',
        'shipping_cost',
        'address',
        'area',
        'order_note',
        'status',
        'customer_name',
        'customer_phone',
        'customer_email',
    ];

        protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

}
