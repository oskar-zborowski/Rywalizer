<?php

namespace App\Models;

class DefaultTypeName extends BaseModel
{
    protected $fillable = [
        'name',
        'description'
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
        'name',
        'description',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function defaultTypes() {
        return $this->hasMany(DefaultType::class);
    }
}
