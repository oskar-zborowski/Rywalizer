<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountActionType extends Model
{
    use HasFactory, Encryptable;

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
