<?php

namespace App\Models;

class FacilityEquipment extends BaseModel
{
    protected $fillable = [
        'facility_id',
        'equipment_id'
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
        'equipment_id',
        'creator_id',
        'editor_id',
        'supervisor_id',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'visible_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function facility() {
        return $this->belongsTo(Facility::class);
    }

    public function equipment() {
        return $this->belongsTo(DefaultType::class, 'equipment_id');
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
