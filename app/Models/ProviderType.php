<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderType extends Model
{
    use HasFactory, Encryptable;

    protected $guarded = [
        'name',
        'is_enabled'
    ];

    protected $encryptable = [
        'name',
        'is_enabled'
    ];

    protected $maxSize = [
        'name' => 9
    ];
}
