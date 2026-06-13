<?php

namespace App\Models;

use Database\Factories\SalesInvoiceFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoice extends Model
{
    /** @use HasFactory<SalesInvoiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'total_amount',
        'deduction',
        'net_total',
        'paid_amount',
        'remaining_amount',
        'customer_id',
        'payment_method_id',
        'user_id',
        'branch_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branches::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function salesInvoiceDetails()
    {
        return $this->hasMany(SalesInvoiceDetail::class);
    }

    public function onlineOrder()
    {
        return $this->hasOne(OnlineOrder::class);
    }

    public function customerTransaction()
    {
        return $this->hasMany(CustomerTransaction::class);
    }



    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}
