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
        'tax_amount',
        'net_total',
        'paid_amount',
        'remaining_amount',
        'status',
        'cancelled_at',
        'cancelled_by',
        'cancellation_reason',
        'customer_id',
        'payment_method_id',
        'user_id',
        'branch_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'cancelled_at' => 'datetime',
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

    public function returns()
    {
        return $this->hasMany(SalesReturn::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(SalesActivityLog::class);
    }

    public function cancelledBy()
    {
        return $this->belongsTo(User::class, 'cancelled_by');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function getPaymentStatusAttribute(): string
    {
        if ((float) $this->remaining_amount <= 0) {
            return 'paid';
        }

        if ((float) $this->paid_amount > 0) {
            return 'partial';
        }

        return 'unpaid';
    }
}
