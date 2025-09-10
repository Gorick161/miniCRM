<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphMany};
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Deal extends Model
{
    use HasFactory;
    protected $fillable = [
        'pipeline_id','stage_id','company_id','title','value_cents',
        'currency','probability','status','expected_close_date','owner_id',
    ];

    protected $casts = [
        'expected_close_date' => 'date',
        'probability' => 'integer',
    ];

    // Relations
    public function pipeline(): BelongsTo { return $this->belongsTo(Pipeline::class); }
    public function stage(): BelongsTo { return $this->belongsTo(Stage::class); }
    public function company(): BelongsTo { return $this->belongsTo(Company::class); }
    public function owner(): BelongsTo { return $this->belongsTo(User::class, 'owner_id'); }

    public function tasks(): MorphMany { return $this->morphMany(Task::class, 'taskable'); }
    public function activities(): MorphMany { return $this->morphMany(Activity::class, 'activityable'); }

    // Accessor: Wert als Euro
    protected function valueEuro(): Attribute
    {
        return Attribute::get(fn () => $this->value_cents / 100);
    }

    // Scopes
    public function scopeOpen($q) { return $q->where('status', 'open'); }
    public function scopeWon($q)  { return $q->where('status', 'won'); }
    public function scopeLost($q) { return $q->where('status', 'lost'); }
}
