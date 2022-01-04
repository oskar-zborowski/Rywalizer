<?php

namespace App\Models;

class FacilityPlaceBooking extends BaseModel
{
    protected $fillable = [
        'facility_place_id',
        'start_date',
        'end_date'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'booking_status_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'user_id',
        'facility_place_id',
        'start_date',
        'end_date',
        'booking_status_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_date' => 'string',
        'end_date' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function facilityPlace() {
        return $this->belongsTo(FacilityPlace::class);
    }

    public function bookingStatus() {
        return $this->belongsTo(DefaultType::class, 'booking_status_id');
    }
}
