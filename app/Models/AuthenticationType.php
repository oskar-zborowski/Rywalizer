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
