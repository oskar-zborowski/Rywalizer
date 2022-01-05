<?php

namespace App\Models;

class AnnouncementPayment extends BaseModel
{
    protected $fillable = [
        'payment_type_id',
        'is_active'
    ];

    protected $guarded = [
        'id',
        'announcement_id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'announcement_id',
        'payment_type_id',
        'creator_id',
        'editor_id',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function announcement() {
        return $this->belongsTo(Announcement::class);
    }

    public function paymentType() {
        return $this->belongsTo(DefaultType::class, 'payment_type_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function announcementParticipants() {
        return $this->hasMany(AnnouncementParticipant::class);
    }
}
