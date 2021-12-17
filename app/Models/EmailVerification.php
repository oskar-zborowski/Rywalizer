<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\Libraries\Validation\Validation;
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

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'token' => 48
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function countMailing() {

        if (Validation::timeComparison($this->updated_at, env('PAUSE_BEFORE_RETRYING')*60, '<=', 'seconds')) {
            throw new ApiException(AuthErrorCode::WAIT_BEFORE_RETRYING());
        }

        $emailSendingCounter = $this->email_sending_counter;

        if ($emailSendingCounter >= 255) {
            $emailSendingCounter = 0;
        }

        return $emailSendingCounter;
    }
}
