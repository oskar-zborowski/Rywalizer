<?php

namespace App\Models;

class FacilityOpeningHour extends BaseModel
{
    protected $fillable = [
        'facility_id',
        'monday_from',
        'monday_to',
        'tuesday_from',
        'tuesday_to',
        'wednesday_from',
        'wednesday_to',
        'thursday_from',
        'thursday_to',
        'friday_from',
        'friday_to',
        'saturday_from',
        'saturday_to',
        'sunday_from',
        'sunday_to'
    ];

    protected $guarded = [
        'id',
        'creator_id',
        'editor_id',
        'supervisor_id',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'facility_id',
        'monday_from',
        'monday_to',
        'tuesday_from',
        'tuesday_to',
        'wednesday_from',
        'wednesday_to',
        'thursday_from',
        'thursday_to',
        'friday_from',
        'friday_to',
        'saturday_from',
        'saturday_to',
        'sunday_from',
        'sunday_to',
        'creator_id',
        'editor_id',
        'supervisor_id',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'monday_from' => 'string',
        'monday_to' => 'string',
        'tuesday_from' => 'string',
        'tuesday_to' => 'string',
        'wednesday_from' => 'string',
        'wednesday_to' => 'string',
        'thursday_from' => 'string',
        'thursday_to' => 'string',
        'friday_from' => 'string',
        'friday_to' => 'string',
        'saturday_from' => 'string',
        'saturday_to' => 'string',
        'sunday_from' => 'string',
        'sunday_to' => 'string',
        'visible_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function facility() {
        return $this->belongsTo(Facility::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }
}
