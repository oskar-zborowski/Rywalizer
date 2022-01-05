<?php

namespace App\Models;

class FacilityPlace extends BaseModel
{
    protected $fillable = [
        'facility_id',
        'facility_place_type_id',
        'name',
        'unit',
        'price_per_unit',
        'minimum_unit_booking',
        'maximum_unit_booking',
        'is_automatically_approved',
        'is_visible'
    ];

    protected $guarded = [
        'id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'facility_id',
        'facility_place_type_id',
        'name',
        'unit',
        'price_per_unit',
        'minimum_unit_booking',
        'maximum_unit_booking',
        'creator_id',
        'editor_id',
        'is_automatically_approved',
        'is_visible',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'unit' => 'int',
        'price_per_unit' => 'int',
        'minimum_unit_booking' => 'int',
        'maximum_unit_booking' => 'int',
        'is_automatically_approved' => 'boolean',
        'is_visible' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function facility() {
        return $this->belongsTo(Facility::class);
    }

    public function facilityPlaceType() {
        return $this->belongsTo(DefaultType::class, 'facility_place_type_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function discountable() {
        return $this->morphMany(Discount::class, 'discountable');
    }

    public function facilityPlaceBookings() {
        return $this->hasMany(FacilityPlaceBooking::class);
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }
}
