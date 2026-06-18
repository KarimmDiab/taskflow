<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    public const TYPES = [
        'purchase',
        'sale',
        'sales_return',
        'purchase_return',
        'transfer_in',
        'transfer_out',
        'adjustment',
    ];

    public const DIRECTIONS = ['in', 'out'];

    protected $fillable = [
        'branch_id',
        'product_id',
        'product_variant_id',
        'movement_type',
        'direction',
        'quantity',
        'quantity_before',
        'quantity_after',
        'unit_cost',
        'unit_price',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
        'movement_date',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'unit_cost' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'movement_date' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function inventoryAdjustment()
    {
        return $this->belongsTo(InventoryAdjustment::class, 'reference_id')
            ->where('reference_type', InventoryAdjustment::class);
    }
}
