<?php

namespace App\Models;

class Friend extends BaseModel
{
    protected $fillable = [
        'responding_user_id'
    ];

    protected $guarded = [
        'id',
        'requesting_user_id',
        'responding_user_displayed_at',
        'requesting_user_displayed_at',
        'confirmed_at',
        'rejected_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'requesting_user_id',
        'responding_user_id',
        'responding_user_displayed_at',
        'requesting_user_displayed_at',
        'confirmed_at',
        'rejected_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'responding_user_displayed_at' => 'string',
        'requesting_user_displayed_at' => 'string',
        'confirmed_at' => 'string',
        'rejected_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function requestingUser() {
        return $this->belongsTo(User::class, 'requesting_user_id');
    }

    public function respondingUser() {
        return $this->belongsTo(User::class, 'responding_user_id');
    }
}
