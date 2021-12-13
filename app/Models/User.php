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
        'last_time_name_changed',
        'last_time_password_changed'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'email',
        'password',
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
        'last_time_name_changed',
        'last_time_password_changed',
        'created_at',
        'updated_at'
    ];

    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    //     'account_deleted_at' => 'datetime',
    //     'account_blocked_at' => 'datetime',
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
        'genderType'
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

    public function userAuthentication() {
        return $this->hasMany(UserAuthentication::class);
    }

    public function privateData() {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'birth_date' => $this->birth_date,
            'address_coordinates' => $this->address_coordinates,
            'telephone' => $this->telephone,
            'facebook_profile' => $this->facebook_profile,
            'instagram_profile' => $this->instagram_profile,
            'gender_type' => $this->genderType()->get(['name'])[0]['name'] ?? null,
            'role_type' => $this->roleType()->get(['name', 'access_level'])[0],
            'last_time_name_changed' => $this->last_time_name_changed,
            'last_time_password_changed' => $this->last_time_password_changed,
        ];
    }

    public function detailedData() {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'login_form' => $this->externalAuthentication()->first() ? $this->externalAuthentication()->first()->providerType()->get('name')[0]['name'] : 'form',
            'email' => $this->email,
            'avatar' => $this->avatar,
            'birth_date' => $this->birth_date,
            'address_coordinates' => $this->address_coordinates,
            'telephone' => $this->telephone,
            'facebook_profile' => $this->facebook_profile,
            'instagram_profile' => $this->instagram_profile,
            'gender_type' => $this->genderType()->get(['name'])[0]['name'] ?? null,
            'role_type' => $this->roleType()->get(['name'])[0],
            'email_verified_at' => $this->email_verified_at,
            'account_deleted_at' => $this->account_deleted_at,
            'account_blocked_at' => $this->account_blocked_at,
            'last_time_name_changed' => $this->last_time_name_changed,
            'last_time_password_changed' => $this->last_time_password_changed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
