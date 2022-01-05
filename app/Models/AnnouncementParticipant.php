<?php

namespace App\Models;

class AnnouncementParticipant extends BaseModel
{
    protected $fillable = [
        'announcement_seat_id',
        'announcement_payment_id'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'joining_status_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'announcement_seat_id',
        'announcement_payment_id',
        'joining_status_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function announcementSeat() {
        return $this->belongsTo(AnnouncementSeat::class);
    }

    public function announcementPayment() {
        return $this->belongsTo(AnnouncementPayment::class);
    }

    public function joiningStatus() {
        return $this->belongsTo(DefaultType::class, 'joining_status_id');
    }

    public function transactionable() {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
}
