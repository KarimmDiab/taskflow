<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesInvoiceDetail extends Model
{
    /** @use HasFactory<\Database\Factories\SalesInvoiceDetailFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id',
        'sales_invoice_id',
        'product_quantity',
        'cost_per_piece',
    ];


    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];


    public function salesInvoice() 
    {
        return $this->belongsTo(SalesInvoice::class);
    }
    public function product() 
    {
        return $this->belongsTo(Product::class);
    }
}
