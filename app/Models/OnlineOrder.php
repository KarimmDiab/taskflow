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

    public const STATUSES = [
        'pending',
        'confirmed',
        'preparing',
        'shipped',
        'delivered',
        'cancelled',
        'returned',
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

    public function getOrderNumberAttribute(): string
    {
        return $this->salesInvoice?->invoice_number ?? 'ORD-'.$this->id;
    }

    public function getSubtotalAttribute(): float
    {
        return (float) ($this->salesInvoice?->total_amount ?? 0);
    }

    public function getGrandTotalAttribute(): float
    {
        return (float) ($this->salesInvoice?->net_total ?? ($this->subtotal + $this->shipping_cost));
    }

}
