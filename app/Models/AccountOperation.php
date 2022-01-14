<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\Libraries\Validation\Validation;
use App\Http\Traits\Encryptable;

class AccountOperation extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'account_operation_type_id',
        'token',
        'email_sending_counter'
    ];

    protected $guarded = [
        'id',
        'operationable_type',
        'operationable_id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'operationable_type',
        'operationable_id',
        'account_operation_type_id',
        'token',
        'email_sending_counter',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'email_sending_counter' => 'int',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'token' => 48
    ];

    public function operationable() {
        return $this->morphTo();
    }

    public function accountOperationType() {
        return $this->belongsTo(DefaultType::class, 'account_operation_type_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    /**
     * Zwrócenie liczby wysłanych maili powiązanych z daną akcją
     * 
     * @param bool $ignorePause flaga określająca czy ma być sprawdzany czas ostatniego wysłania maila
     * 
     * @return int
     */
    public function countMailing(bool $ignorePause = false): int {

        if (!$ignorePause && Validation::timeComparison($this->updated_at, env('PAUSE_BEFORE_RESENDING_EMAIL')*60, '<=', 'seconds')) {
            throw new ApiException(AuthErrorCode::WAIT_BEFORE_RETRYING());
        }

        $emailSendingCounter = (int) $this->email_sending_counter;

        if ($emailSendingCounter >= 255) {
            $emailSendingCounter = 0;
        }

        return $emailSendingCounter;
    }
}
