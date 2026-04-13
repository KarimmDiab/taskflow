<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpensesItem extends Model
{
    /** @use HasFactory<\Database\Factories\ExpensesItemFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'expenses_name',
        
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function expensesDetails ()
    {
        return $this->hasMany(ExpensesDetail::class);
    }
}
