<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'return_number',
        'sales_invoice_id',
        'return_type',
        'refund_method',
        'status',
        'reason',
        'subtotal_amount',
        'discount_amount',
        'tax_amount',
        'return_amount',
        'created_by',
        'approved_by',
        'approved_at',
        'approval_note',
    ];

    protected $casts = [
        'subtotal_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'return_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function activityLogs()
    {
        return $this->hasMany(SalesActivityLog::class);
    }
}
