<?php

namespace App\Models;

use Mehradsadeghi\FilterQueryString\FilterQueryString;

class Area extends BaseModel
{
    use FilterQueryString;

    protected $fillable = [
        'name',
        'area_type_id'
    ];

    protected $guarded = [
        'id',
        'boundary',
        'parent_id',
        'creator_id',
        'editor_id',
        'supervisor_id',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'name',
        'boundary',
        'area_type_id',
        'parent_id',
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

    protected $filters = [
        'sort',
        'like',
        'in'
    ];

    public function areaType() {
        return $this->belongsTo(DefaultType::class, 'area_type_id');
    }

    public function parent() {
        return $this->belongsTo(Area::class, 'parent_id');
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

    public function partners() {
        return $this->hasMany(Partner::class, 'city_id');
    }

    public function facilities() {
        return $this->hasMany(Facility::class, 'city_id');
    }

    public function getBasicInformation() {
        return [
            'area' => [
                'id' => $this->id,
                'name' => $this->name,
                'boundary' => $this->boundary,
                'area_type' => [
                    'id' => $this->areaType->id,
                    'name' => $this->areaType->name,
                    'description' => $this->areaType->description_simple
                ]
            ]
        ];
    }
}
