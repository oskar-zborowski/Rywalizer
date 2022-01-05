<?php

namespace App\Models;

class AnnouncementSeat extends BaseModel
{
    protected $fillable = [
        'sports_position_id',
        'maximum_seats_number',
        'is_active'
    ];

    protected $guarded = [
        'id',
        'announcement_id',
        'occupied_seats_counter',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'announcement_id',
        'sports_position_id',
        'occupied_seats_counter',
        'maximum_seats_number',
        'creator_id',
        'editor_id',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'occupied_seats_counter' => 'int',
        'maximum_seats_number' => 'int',
        'is_active' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function announcement() {
        return $this->belongsTo(Announcement::class);
    }

    public function sportsPosition() {
        return $this->belongsTo(SportsPosition::class);
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
