<?php

namespace App\Models;

class RatingUsefulness extends BaseModel
{
    protected $fillable = [
        'rating_id',
        'is_usefulness'
    ];

    protected $guarded = [
        'id',
        'evaluator_type',
        'evaluator_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'evaluator_type',
        'evaluator_id',
        'rating_id',
        'is_usefulness',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_usefulness' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function evaluator() {
        return $this->morphTo();
    }

    public function rating() {
        return $this->belongsTo(Rating::class);
    }
}
