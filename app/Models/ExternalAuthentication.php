<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class ExternalAuthentication extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'user_id',
        'external_authentication_id',
        'provider_type_id',
        'created_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'external_authentication_id',
        'provider_type_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'string'
    ];

    public $timestamps = false;

    protected $encryptable = [
        'external_authentication_id' => 255
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function provider() {
        return $this->belongsTo(DefaultType::class, 'provider_id');
    }
}
