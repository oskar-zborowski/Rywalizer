<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class AccountOperationType extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'name'
    ];

    protected $hidden = [
        'id',
        'name'
    ];

    protected $encryptable = [
        'name' => 18
    ];

    public function accountOperation() {
        return $this->hasMany(AccountOperation::class);
    }
}
