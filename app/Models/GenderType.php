<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderType extends Model
{
    use HasFactory, Encryptable;

    protected $guarded = [
        'name'
    ];

    protected $encryptable = [
        'name'
    ];

    protected $maxSize = [
        'name' => 6
    ];
}
