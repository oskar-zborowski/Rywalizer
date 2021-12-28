<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class GenderType extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'name',
        'description',
        'icon'
    ];

    protected $hidden = [
        'name'
    ];

    protected $encryptable = [
        'name' => 6,
        'description' => 12,
        'icon' => 15
    ];

    public function user() {
        return $this->hasMany(User::class);
    }
}
