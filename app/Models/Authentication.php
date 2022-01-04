<?php

namespace App\Models;

class Authentication extends BaseModel
{
    protected $guarded = [
        'id',
        'user_id',
        'authentication_type_id',
        'device_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'authentication_type_id',
        'device_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function authenticationType() {
        return $this->belongsTo(DefaultType::class, 'authentication_type_id');
    }

    public function device() {
        return $this->belongsTo(Device::class);
    }

    /**
     * Zwrócenie prywatnych informacji o uwierzytelnianiu użytkownika
     * 
     * @return array
     */
    public function privateInformation(): array {
        return [
            'created_at' => $this->created_at,
            'device' => [
                'ip' => $this->device->ip,
                'os_name' => $this->device->os_name,
                'os_version' => $this->device->os_version,
                'browser_name' => $this->device->browser_name
            ],
            'authentication_type' => $this->authenticationType
        ];
    }

    /**
     * Zwrócenie szczegółowych informacji o uwierzytelnianiu użytkownika
     * 
     * @return array
     */
    public function detailedInformation(): array {
        return [
            'created_at' => $this->created_at,
            'device' => [
                'ip' => $this->device->ip,
                'uuid' => $this->device->uuid,
                'os_name' => $this->device->os_name,
                'os_version' => $this->device->os_version,
                'browser_name' => $this->device->browser_name,
                'browser_version' => $this->device->browser_version,
                'created_at' => $this->device->created_at,
                'updated_at' => $this->device->updated_at
            ],
            'authentication_type' => $this->authenticationType
        ];
    }
}
