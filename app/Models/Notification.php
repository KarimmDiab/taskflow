<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'module',
        'reference_id',
        'is_read',
        'read_at',
        'data',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'data' => 'array',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeUnread(Builder $query): Builder
    {
        return $query->where('is_read', false);
    }

    public function scopeRead(Builder $query): Builder
    {
        return $query->where('is_read', true);
    }

    public function scopeModule(Builder $query, string $module): Builder
    {
        return $query->where('module', $module);
    }

    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    public function scopeLatestFirst(Builder $query): Builder
    {
        return $query->latest('created_at');
    }
}
