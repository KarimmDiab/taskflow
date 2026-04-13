<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoice extends Model
{
    /** @use HasFactory<\Database\Factories\SalesInvoiceFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total_amount',
        'deduction',
        'net_total',
        'paid_amount',
        'remaining_amount',
        'payment_method',
        'user_id',
        'branch_id'
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

    public function salesInvoiceDetails()
    {
        return $this->hasMany(SalesInvoiceDetail::class);
    }

    public function customerTransaction()
    {
        return $this->hasMany(CustomerTransaction::class);
    }

    public function customerSalesInvoice()
    {
        return $this->hasMany(CustomerSalesInvoice::class);
    }
}
