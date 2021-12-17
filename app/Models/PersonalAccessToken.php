<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAccessToken extends Model
{
    use HasFactory;

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
        'last_used_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'tokenable_id');
    }
}
