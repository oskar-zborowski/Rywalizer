<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenderType extends Model
{
    use HasFactory;

    protected $guarded = [
        'name'
    ];
}