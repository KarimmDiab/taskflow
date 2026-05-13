<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    /** @use HasFactory<\Database\Factories\SizeFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'size_name',
        'sort_order',
        'is_active',
    ];

        protected $casts =[
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function productVariants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    

}
