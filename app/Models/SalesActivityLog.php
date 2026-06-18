<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalesActivityLog extends Model
{
    protected $fillable = [
        'sales_invoice_id',
        'sales_return_id',
        'user_id',
        'action',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function invoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'sales_invoice_id');
    }

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
