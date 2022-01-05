<?php

namespace App\Models;

class UserAgreement extends BaseModel
{
    protected $fillable = [
        'agreement_id'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'canceled_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'agreement_id',
        'canceled_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'canceled_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function agreement() {
        return $this->belongsTo(Agreement::class);
    }
}
