<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    use HasFactory, Encryptable;

    protected $guarded = [
        'name',
        'access_level'
    ];

    protected $encryptable = [
        'name'
    ];

    protected $maxSize = [
        'name' => 15
    ];
}