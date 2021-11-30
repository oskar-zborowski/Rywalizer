<?php

namespace App\Models;

use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    use HasFactory, Encryptable;

    protected $fillable = [
        'token',
        'email_sending_counter'
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
        'token',
        'email_sending_counter',
        'created_at',
        'updated_at'
    ];

    // protected $casts = [
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime'
    // ];

    protected $encryptable = [
        'token'
    ];

    protected $maxSize = [
        'token' => 48
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
