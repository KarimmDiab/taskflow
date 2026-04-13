<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoice extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseInvoiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_invoice_date',
        'total_amount',
        'paid_amount',
        'payment_method',
        'remaining_amount',
        'product_image',
        'branch_id',
        'supplier_id'
    ];

    protected $casts = [
        'purchase_invoice_date' => 'datetime', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function supplier () 
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseInvoiceDetails () 
    {
        return $this->hasMany(PurchaseInvoiceDetail::class);
    }

    public function supplierPayment () 
    {
        return $this->hasMany(SupplierPayment::class);
    }
}
