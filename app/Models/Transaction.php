<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Transaction extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'transactionable_type',
        'transactionable_id',
        'number',
        'regular_price',
        'total_amount',
        'system_amount',
        'partner_amount',
        'order_id',
        'session_id',
        'discount_id',
        'transaction_status_id',
        'confirmed_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'transactionable_type',
        'transactionable_id',
        'number',
        'regular_price',
        'total_amount',
        'system_amount',
        'partner_amount',
        'order_id',
        'session_id',
        'discount_id',
        'transaction_status_id',
        'confirmed_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'regular_price' => 'int',
        'total_amount' => 'int',
        'system_amount' => 'int',
        'partner_amount' => 'int',
        'confirmed_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'order_id' => 48,
        'session_id' => 48
    ];

    public function transactionable() {
        return $this->morphTo();
    }

    public function discount() {
        return $this->belongsTo(Discount::class);
    }

    public function transactionStatus() {
        return $this->belongsTo(DefaultType::class, 'transaction_status_id');
    }
}
