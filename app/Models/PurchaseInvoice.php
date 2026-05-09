<?php

namespace App\Models;

use Database\Factories\PurchaseInvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoice extends Model
{
    /** @use HasFactory<PurchaseInvoiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'purchase_invoice_date',
        'total_amount',
        'paid_amount',
        'payment_method',
        'remaining_amount',
        'invoice_image',
        'branch_id',
        'supplier_id',
        'user_id',
    ];

    protected $casts = [
        'purchase_invoice_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function purchaseInvoiceDetails()
    {
        return $this->hasMany(PurchaseInvoiceDetail::class);
    }

    public function supplierPayment()
    {
        return $this->hasMany(SupplierPayment::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
