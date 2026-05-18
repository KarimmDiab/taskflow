<?php

namespace App\Models;

use Database\Factories\ShippingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shipping extends Model
{
    /** @use HasFactory<ShippingFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'city_name',
        'shipping_cost',
        'is_active',
        'estimated_days',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }

    
}
