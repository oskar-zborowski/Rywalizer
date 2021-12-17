<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountAction extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'account_action_type_id',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $with = [
        'accountActionType'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function accountActionType() {
        return $this->belongsTo(AccountActionType::class);
    }
}
