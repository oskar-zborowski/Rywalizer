<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Device extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'ip',
        'uuid',
        'os_name',
        'os_version',
        'browser_name',
        'browser_version'
    ];

    protected $guarded = [
        'id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'ip',
        'uuid',
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
        'ip' => 15,
        'uuid' => 48,
        'os_name' => 15,
        'os_version' => 24,
        'browser_name' => 18,
        'browser_version' => 24
    ];

    public function authentication() {
        return $this->hasMany(Authentication::class);
    }
}
