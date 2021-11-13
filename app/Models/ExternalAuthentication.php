<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalAuthentication extends Model
{
    use HasFactory;

    protected $fillable = [
        'authentication_id',
        'user_id',
        'provider_type_id'
    ];
}
