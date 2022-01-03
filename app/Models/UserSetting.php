<?php

namespace App\Models;

class UserSetting extends BaseModel
{
    protected $fillable = [
        'is_visible_in_comments'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'is_visible_in_comments',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_visible_in_comments' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
