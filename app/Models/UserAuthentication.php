<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthentication extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'authentication_type_id'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'device_id',
        'authentication_type_id',
        'created_at',
        'updated_at'
    ];

    // protected $casts = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function device() {
        return $this->belongsTo(Device::class);
    }

    public function authenticationType() {
        return $this->belongsTo(AuthenticationType::class);
    }
}
