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
        'is_enabled'
    ];

    protected $hidden = [
        'id',
        'name',
        'is_enabled'
    ];

    protected $encryptable = [
        'name'
    ];

    protected $maxSize = [
        'name' => 9
    ];

    public function externalAuthentication() {
        return $this->hasMany(ExternalAuthentication::class);
    }
}
