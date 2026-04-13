<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoiceDetail extends Model
{
    /** @use HasFactory<\Database\Factories\PurchaseInvoiceDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'purchase_invoice_id',
        'product_quantity',
        'cost_per_piece'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function purchaseInvoice() 
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }

    public function product() 
    {
        return $this->belongsTo(Product::class);
    }
}
