<?php

namespace App\Models;

class AccountAction extends BaseModel
{
    protected $fillable = [
        'founder_id',
        'account_action_type_id',
        'expires_at'
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
        'founder_id',
        'account_action_type_id',
        'updated_at'
    ];

    protected $casts = [
        'expires_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $with = [
        'accountActionType'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function founder() {
        return $this->belongsTo(User::class);
    }

    public function accountActionType() {
        return $this->belongsTo(AccountActionType::class);
    }
}
