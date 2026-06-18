<?php

namespace App\Models;

use Database\Factories\PurchaseInvoiceDetailFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceDetail extends Model
{
    /** @use HasFactory<PurchaseInvoiceDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_variant_id',
        'purchase_invoice_id',
        'branch_id',
        'product_quantity',
        'unit_cost',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function purchaseReturnItems()
    {
        return $this->hasMany(PurchaseReturnItem::class, 'purchase_invoice_item_id');
    }

    public function getReturnedQuantityAttribute(): int
    {
        return (int) $this->purchaseReturnItems()->sum('quantity');
    }

    public function getReturnableQuantityAttribute(): int
    {
        return max((int) $this->product_quantity - $this->returned_quantity, 0);
    }
}
