<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerTransaction extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerTransactionFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_invoice_id',
        'customer_id',
        'paid_amount',
        'notes',
        'user_id',
        'payment_date'
    ];

    protected $casts = [
        'payment_date' => 'datetime', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class);
    }

    public function customer() // ✅ مفرد
    {
        return $this->belongsTo(Customer::class);
    }

    public function user() // ✅ مفرد
    {
        return $this->belongsTo(User::class);
    }

}
