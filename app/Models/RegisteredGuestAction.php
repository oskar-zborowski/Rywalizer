<?php

namespace App\Models;

class RegisteredGuestAction extends BaseModel
{
    protected $guarded = [
        'id',
        'device_id',
        'action_type_id',
        'created_at'
    ];

    protected $hidden = [
        'id',
        'device_id',
        'action_type_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'string'
    ];

    public $timestamps = false;

    public function device() {
        return $this->belongsTo(Device::class);
    }

    public function actionType() {
        return $this->belongsTo(DefaultType::class, 'action_type_id');
    }
}
