<?php

namespace App\Models;

class Rating extends BaseModel
{
    protected $fillable = [
        'evaluable_type',
        'evaluable_id',
        'answer_to_id',
        'rating',
        'comment'
    ];

    protected $guarded = [
        'id',
        'evaluator_type',
        'evaluator_id',
        'positive_counter',
        'negative_counter',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'evaluable_type',
        'evaluable_id',
        'evaluator_type',
        'evaluator_id',
        'answer_to_id',
        'rating',
        'comment',
        'positive_counter',
        'negative_counter',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'rating' => 'int',
        'positive_counter' => 'int',
        'negative_counter' => 'int',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function evaluable() {
        return $this->morphTo(__FUNCTION__, 'evaluable_type', 'evaluable_id');
    }

    public function evaluator() {
        return $this->morphTo(__FUNCTION__, 'evaluator_type', 'evaluator_id');
    }

    public function answerTo() {
        return $this->belongsTo(Rating::class, 'answer_to_id');
    }

    public function ratingUsefulness() {
        return $this->hasMany(RatingUseFulness::class);
    }
}
