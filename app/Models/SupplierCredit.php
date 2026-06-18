<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupplierCredit extends Model
{
    protected $fillable = [
        'credit_number',
        'supplier_id',
        'purchase_invoice_id',
        'purchase_return_id',
        'amount',
        'remaining_amount',
        'credit_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'credit_date' => 'date',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
