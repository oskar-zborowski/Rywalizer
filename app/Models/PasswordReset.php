<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Http\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
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

    public function resetPassword($request) {

        if (Validation::timeComparison($this->updated_at, env('EMAIL_TOKEN_LIFETIME'), '>')) {
            throw new ApiException(AuthErrorCode::PASSWORD_RESET_TOKEN_HAS_EXPIRED());
        }

        $this->user()->first()->update([
            'password' => $request->password,
            'last_time_password_changed' => now()
        ]);

        if (!$request->do_not_logout) {
            PersonalAccessToken::where('tokenable_id', $this->user_id)->delete();
        }

        $this->delete();

        JsonResponse::sendSuccess();
    }
}
