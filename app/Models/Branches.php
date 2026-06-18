<?php

namespace App\Models;

use Database\Factories\BranchesFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branches extends Model
{
    /** @use HasFactory<BranchesFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_name',
        'branch_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchase_invoices()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    public function sales_invoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function stockTransfers()
    {
        return $this->hasMany(StockTransfer::class);
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'branch_id');
    }

    public function inventoryAdjustments()
    {
        return $this->hasMany(InventoryAdjustment::class, 'branch_id');
    }
}
