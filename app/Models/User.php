<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Encryptable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'gender_type_id',
        'role_type_id',
        'birth_date',
        'email_verified_at',
        'account_blocked_at',
        'account_deleted_at'
    ];

    protected $guarded = [
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'account_blocked_at' => 'datetime',
        'account_deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $encryptable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'birth_date'
    ];

    protected $maxSize = [
        'first_name' => 30,
        'last_name' => 30,
        'email' => 254,
        'avatar' => 189,
        'birth_date' => 10
    ];

    public function roleType() {
        return $this->belongsTo(RoleType::class);
    }

    public function genderType() {
        return $this->belongsTo(GenderType::class);
    }
}