<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthenticationType extends Model
{
    use HasFactory, Encryptable;

    protected $guarded = [
        'id',
        'name'
    ];

    protected $hidden = [
        'id'
    ];

    protected $encryptable = [
        'name'
    ];

    protected $maxSize = [
        'name' => 15
    ];

    public function userAuthentication() {
        return $this->hasMany(UserAuthentication::class);
    }
}
