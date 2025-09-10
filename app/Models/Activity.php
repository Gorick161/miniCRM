<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{MorphTo, BelongsTo};
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;
    protected $fillable = [
        'activityable_type', 'activityable_id',
        'type', 'body', 'happened_at', 'user_id',
    ];

    protected $casts = [
        'happened_at' => 'datetime',
    ];

    public function activityable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
