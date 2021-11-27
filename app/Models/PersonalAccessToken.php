<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory, Encryptable;

    protected $fillable = [
        'refresh_token'
    ];

    protected $guarded = [
        'id',
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
        'refresh_token',
        'abilities',
        'last_used_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected $encryptable = [
        'refresh_token'
    ];

    protected $maxSize = [
        'refresh_token' => 48
    ];

    public function user() {
        return $this->belongsTo(User::class, 'tokenable_id');
    }
}
