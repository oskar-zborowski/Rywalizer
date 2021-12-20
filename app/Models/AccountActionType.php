<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class AccountActionType extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'name',
        'description',
        'description_admin',
        'period'
    ];

    protected $hidden = [
        'name',
        'description'
    ];

    protected $encryptable = [
        'name' => 27,
        'description' => 39,
        'description_admin' => 27
    ];

    public function accountAction() {
        return $this->hasMany(AccountAction::class);
    }
}
