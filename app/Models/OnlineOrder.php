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
        'subtotal',
        'discount_amount',
        'discount_type',
        'coupon_id',
        'coupon_code',
        'grand_total',
        'net_total',
        'coupon_counted_at',
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
        'coupon_counted_at' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'net_total' => 'decimal:2',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function shipping()
    {
        return $this->belongsTo(Shipping::class);
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function getOrderNumberAttribute(): string
    {
        return $this->salesInvoice?->invoice_number ?? 'ORD-'.$this->id;
    }

    public function getSubtotalAttribute(): float
    {
        $subtotal = (float) ($this->attributes['subtotal'] ?? 0);

        return $subtotal > 0 ? $subtotal : (float) ($this->salesInvoice?->total_amount ?? 0);
    }

    public function getGrandTotalAttribute(): float
    {
        $grandTotal = (float) ($this->attributes['grand_total'] ?? 0);

        return $grandTotal > 0 ? $grandTotal : (float) ($this->salesInvoice?->net_total ?? ($this->subtotal + $this->shipping_cost));
    }

}
