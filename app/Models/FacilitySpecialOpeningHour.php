<?php

namespace App\Models;

class FacilitySpecialOpeningHour extends BaseModel
{
    protected $fillable = [
        'facility_id',
        'date',
        'open_from',
        'open_to',
        'is_closed'
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
        'date',
        'open_from',
        'open_to',
        'creator_id',
        'editor_id',
        'supervisor_id',
        'is_closed',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'date' => 'string',
        'open_from' => 'string',
        'open_to' => 'string',
        'is_closed' => 'boolean',
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
}
