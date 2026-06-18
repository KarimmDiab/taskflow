<?php

namespace App\Models;

use Database\Factories\SalesInvoiceDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceDetail extends Model
{
    /** @use HasFactory<SalesInvoiceDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'sales_invoice_id',
        'product_quantity',
        'unit_price',
        'item_discount_type',
        'item_discount_value',
        'item_discount_amount',
        'discount_amount',
        'cost_price',
        'line_total',
        'line_total_after_discount',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'unit_price' => 'decimal:2',
        'item_discount_value' => 'decimal:2',
        'item_discount_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'line_total' => 'decimal:2',
        'line_total_after_discount' => 'decimal:2',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function returnItems()
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    public function getReturnedQuantityAttribute(): int
    {
        if ($this->relationLoaded('returnItems')) {
            return (int) $this->returnItems
                ->filter(fn (SalesReturnItem $item) => in_array($item->salesReturn?->status, ['approved', 'completed'], true))
                ->sum('quantity');
        }

        return (int) $this->returnItems()
            ->whereHas('salesReturn', fn ($query) => $query->whereIn('status', ['approved', 'completed']))
            ->sum('quantity');
    }
}
