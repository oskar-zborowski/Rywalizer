<?php

namespace App\Models;

class AccountActionType extends BaseModel
{
    protected $fillable = [
        'period'
    ];

    protected $guarded = [
        'id',
        'account_action_type_id'
    ];

    protected $hidden = [
        'id',
        'account_action_type_id',
        'period'
    ];

    protected $casts = [
        'period' => 'int'
    ];

    public $timestamps = false;

    public function accountActionType() {
        return $this->belongsTo(DefaultType::class, 'account_action_type_id');
    }

    public function accountsActions() {
        return $this->hasMany(AccountAction::class);
    }
}
