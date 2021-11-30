<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExternalAuthentication extends Model
{
    use HasFactory, Encryptable;

    protected $fillable = [
        'authentication_id',
        'provider_type_id'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'authentication_id',
        'user_id',
        'provider_type_id',
        'created_at',
        'updated_at'
    ];

    // protected $casts = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];

    protected $encryptable = [
        'authentication_id'
    ];

    protected $maxSize = [
        'authentication_id' => 254
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function providerType() {
        return $this->belongsTo(ProviderType::class);
    }
}
