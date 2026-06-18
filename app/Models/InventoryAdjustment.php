<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryAdjustment extends Model
{
    public const TYPES = [
        'shortage',
        'damaged',
        'overstock',
        'data_entry_error',
        'stock_count',
        'other',
    ];

    protected $fillable = [
        'adjustment_number',
        'branch_id',
        'product_variant_id',
        'quantity_before',
        'quantity_after',
        'adjustment_quantity',
        'adjustment_type',
        'reason',
        'notes',
        'status',
        'created_by',
        'adjustment_date',
    ];

    protected $casts = [
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'adjustment_quantity' => 'integer',
        'adjustment_date' => 'datetime',
    ];

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function productVariant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function stockMovement()
    {
        return $this->morphOne(StockMovement::class, 'reference');
    }

    public function stockMovements()
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }
}
