<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    use HasFactory, Encryptable;

    protected $guarded = [
        'id',
        'name',
        'access_level'
    ];

    protected $encryptable = [
        'name',
        'access_level'
    ];

    protected $maxSize = [
        'name' => 15,
        'access_level' => 3
    ];

    public function user() {
        return $this->hasMany(User::class);
    }
}
