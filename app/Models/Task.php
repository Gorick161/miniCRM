<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{MorphTo, BelongsTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'taskable_type', 'taskable_id',
        'title', 'due_at', 'status', 'assigned_to',
    ];

    protected $casts = [
        'due_at' => 'datetime',
    ];

    public function taskable(): MorphTo
    {
        return $this->morphTo();
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Scopes
    public function scopeOpen($q) { return $q->where('status', 'open'); }
    public function scopeDueSoon($q) { return $q->whereNotNull('due_at')->orderBy('due_at'); }
}
