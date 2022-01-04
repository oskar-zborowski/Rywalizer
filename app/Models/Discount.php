<?php

namespace App\Models;

class Discount extends BaseModel
{
    protected $fillable = [
        'discountable_type',
        'discountable_id',
        'discount_code_id',

    ];

    protected $guarded = [
        'id',
        'creator_id',
        'created_at'
    ];

    protected $hidden = [
        'id',
        'discountable_type',
        'discountable_id',
        'discount_code_id',
        'creator_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'string',
    ];

    public $timestamps = true;

    public function discountable() {
        return $this->morphTo();
    }

    public function discountCode() {
        return $this->belongsTo(DiscountCode::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}
