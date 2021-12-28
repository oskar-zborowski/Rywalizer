<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class RoleType extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'name',
        'access_level'
    ];

    protected $encryptable = [
        'name' => 15,
        'access_level' => 3
    ];

    public function user() {
        return $this->hasMany(User::class);
    }
}
