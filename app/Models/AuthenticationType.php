<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class AuthenticationType extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'name',
        'description'
    ];

    protected $hidden = [
        'id',
        'name'
    ];

    protected $encryptable = [
        'name' => 18,
        'description' => 30
    ];

    public function authentication() {
        return $this->hasMany(Authentication::class);
    }
}
