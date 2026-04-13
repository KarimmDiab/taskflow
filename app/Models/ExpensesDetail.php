<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpensesDetail extends Model
{
    /** @use HasFactory<\Database\Factories\ExpensesDetailFactory> */
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'expenses_date',
        'expenses_cost',
        'expenses_note',
        'expenses_image',
        'expenses_item_id'
    ];

    protected $casts = [
        'expenses_date' => 'datetime', 
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function expensesItem() 
    {
        return $this->belongsTo(ExpensesItem::class);
    }
}
