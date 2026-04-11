<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branches extends Model
{
    /** @use HasFactory<\Database\Factories\BranchesFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_name',
        'branch_address',
    ];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchase_invoices()
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    
    public function sales_invoices()
    {
        return $this->hasMany(SalesInvoice::class);
    }
}
