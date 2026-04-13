<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_name',
        'supplier_phone',
        'supplier_address'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function purchaseInvoice()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    public function supplierPayment()
    {
        return $this->hasMany(SupplierPayment::class);
    }
}
