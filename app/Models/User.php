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

    protected $casts = [
        'email_verified_at' => 'string',
        'account_deleted_at' => 'string',
        'account_blocked_at' => 'string',
        'last_time_name_changed' => 'string',
        'last_time_password_changed' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

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

    public function privateData(): array {
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
            'gender' => $this->genderType()->get('name')[0] ?? null,
            'role' => $this->roleType()->get(['name', 'access_level'])[0],
            'last_time_name_changed' => $this->last_time_name_changed,
            'last_time_password_changed' => $this->last_time_password_changed
        ];
    }

    public function detailedData(): array {

        $loginForm = null;
        $externalAuthentication = $this->externalAuthentication()->get();

        if ($this->password) {
            $loginForm[] = 'FORM';
        }

        foreach ($externalAuthentication as $eA) {
            $loginForm[] = $eA->providerType()->get('name')[0]['name'];
        }

        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'login_form' => $loginForm,
            'email' => $this->email,
            'avatar' => $this->avatar,
            'birth_date' => $this->birth_date,
            'address_coordinates' => $this->address_coordinates,
            'telephone' => $this->telephone,
            'facebook_profile' => $this->facebook_profile,
            'instagram_profile' => $this->instagram_profile,
            'gender' => $this->genderType()->get('name')[0] ?? null,
            'role' => $this->roleType()->get(['name', 'access_level'])[0],
            'email_verified_at' => $this->email_verified_at,
            'account_deleted_at' => $this->account_deleted_at,
            'account_blocked_at' => $this->account_blocked_at,
            'last_time_name_changed' => $this->last_time_name_changed,
            'last_time_password_changed' => $this->last_time_password_changed,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'authentication' => $this->authentication(['ip', 'uuid', 'os_name', 'os_version', 'browser_name', 'browser_version'], true)
        ];
    }

    public function authentication($deviceFields = '*', bool $withAuthenticationType = false): ?array {

        $authentication = null;
        $userAuthentication = $this->userAuthentication()->get();

        $i = 0;

        foreach ($userAuthentication as $uA) {

            $device = $uA->device()->get($deviceFields);

            $authentication[$i] = [
                'device' => $device[0],
                'date' => $uA['created_at']
            ];

            if ($withAuthenticationType) {
                $authentication[$i]['type'] = $uA->authenticationType()->get('name')[0]['name'];
            }

            $i++;
        }

        return $authentication;
    }
}
