<?php

namespace App\Models;

class Commission extends BaseModel
{
    protected $fillable = [
        'name',
        'descritpion',
        'signature',
        'value',
        'start_date',
        'end_date'
    ];

    protected $guarded = [
        'id',
        'version',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'name',
        'descritpion',
        'signature',
        'version',
        'value',
        'start_date',
        'end_date',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'version' => 'int',
        'value' => 'float',
        'start_date' => 'string',
        'end_date' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function partnersSettings() {
        return $this->hasMany(PartnerSetting::class);
    }
}
