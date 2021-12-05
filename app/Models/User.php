<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, Encryptable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'avatar',
        'birth_date',
        'address_coordinates',
        'telephone',
        'facebook_profile',
        'instagram_profile',
        'gender_type_id',
        'role_type_id',
        'email_verified_at',
        'account_deleted_at',
        'account_blocked_at',
        'last_logged_in',
        'last_time_name_changed',
        'last_time_password_changed'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password',
        'gender_type_id',
        'role_type_id',
        'email_verified_at',
        'account_deleted_at',
        'account_blocked_at',
        'created_at',
        'updated_at'
    ];

    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'account_deleted_at' => 'datetime',
    //     'account_blocked_at' => 'datetime',
    //     'last_logged_in' => 'datetime',
    //     'last_time_name_changed' => 'datetime',
    //     'last_time_password_changed' => 'datetime',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];

    protected $encryptable = [
        'first_name',
        'last_name',
        'email',
        'avatar',
        'birth_date',
        'address_coordinates',
        'telephone',
        'facebook_profile',
        'instagram_profile'
    ];

    protected $maxSize = [
        'first_name' => 30,
        'last_name' => 30,
        'email' => 254,
        'avatar' => 48,
        'birth_date' => 10,
        'address_coordinates' => 15,
        'telephone' => 24,
        'facebook_profile' => 255,
        'instagram_profile' => 255
    ];

    protected $with = [
        'GenderType',
        'RoleType'
    ];

    public function genderType() {
        return $this->belongsTo(GenderType::class);
    }

    public function roleType() {
        return $this->belongsTo(RoleType::class);
    }

    public function personalAccessToken() {
        return $this->hasMany(PersonalAccessToken::class, 'tokenable_id');
    }

    public function externalAuthentication() {
        return $this->hasOne(ExternalAuthentication::class);
    }

    public function passwordReset() {
        return $this->hasOne(PasswordReset::class);
    }

    public function emailVerification() {
        return $this->hasOne(EmailVerification::class);
    }
}
