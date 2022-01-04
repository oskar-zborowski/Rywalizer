<?php

namespace App\Models;

class AccountAction extends BaseModel
{
    protected $guarded = [
        'id',
        'actionable_type',
        'actionable_id',
        'account_action_type_id',
        'expires_at',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'actionable_type',
        'actionable_id',
        'account_action_type_id',
        'expires_at',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'expires_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function actionable() {
        return $this->morphTo();
    }

    public function accountActionType() {
        return $this->belongsTo(AccountActionType::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }
}
