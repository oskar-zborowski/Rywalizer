<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Device extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'os_name',
        'os_version',
        'browser_name',
        'browser_version'
    ];

    protected $guarded = [
        'id',
        'uuid',
        'ip',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'uuid',
        'ip',
        'os_name',
        'os_version',
        'browser_name',
        'browser_version',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'uuid' => 48,
        'ip' => 15,
        'os_name' => 30,
        'os_version' => 30,
        'browser_name' => 30,
        'browser_version' => 30
    ];

    public function authentications() {
        return $this->hasMany(Authentication::class);
    }

    public function registeredGuestActions() {
        return $this->hasMany(RegisteredGuestAction::class);
    }
}
