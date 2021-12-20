<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class ExternalAuthentication extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'exteral_authentication_id',
        'provider_type_id'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'external_authentication_id',
        'user_id',
        'provider_type_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'external_authentication_id' => 255
    ];

    protected $with = [
        'providerType'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function providerType() {
        return $this->belongsTo(ProviderType::class);
    }
}
