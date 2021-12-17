<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderType extends Model
{
    use HasFactory, Encryptable;

    protected $guarded = [
        'id',
        'name',
        'icon',
        'is_enabled'
    ];

    protected $hidden = [
        'id'
    ];

    protected $encryptable = [
        'name' => 9,
        'icon' => 18
    ];

    public function externalAuthentication() {
        return $this->hasMany(ExternalAuthentication::class);
    }
}
