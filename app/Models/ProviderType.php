<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class ProviderType extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'name',
        'icon',
        'is_enabled'
    ];

    protected $hidden = [
        'id',
        'is_enabled'
    ];

    protected $encryptable = [
        'name' => 9,
        'icon' => 18
    ];

    public function externalAuthentication() {
        return $this->hasMany(ExternalAuthentication::class);
    }

    /**
     * Zwrócenie szczegółowych informacji o wykorzystywanych zewnętrznych systemach uwierzytelniających
     * 
     * @return array
     */
    public function detailedInformation(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'icon' => $this->icon,
            'is_enabled' => (bool) $this->is_enabled
        ];
    }
}
