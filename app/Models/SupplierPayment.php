<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierPayment extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierPaymentFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_date',
        'paid_amount',
        'note',
        'purchase_invoice_id',
        'supplier_id',
        'user_id'
    ];

    protected $casts = [
        'payment_date' => 'datetime', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    public function purchaseInvoice() 
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function supplier() 
    {
        return $this->belongsTo(Supplier::class);
    }
}
