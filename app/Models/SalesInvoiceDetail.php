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
        'discount_amount',
        'cost_price',
        'line_total',
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
