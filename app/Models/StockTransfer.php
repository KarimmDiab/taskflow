<?php

namespace App\Models;

use Database\Factories\StockTransferFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StockTransfer extends Model
{
    /** @use HasFactory<StockTransferFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'from_branch_id',
        'to_branch_id',
        'transfer_date',
        'user_id',
        'notes',
    ];

    protected $casts = [
        'transfer_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function fromBranch()
    {
        return $this->belongsTo(Branches::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(Branches::class, 'to_branch_id');
    }

    public function items()
    {
        return $this->hasMany(StockTransferItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
