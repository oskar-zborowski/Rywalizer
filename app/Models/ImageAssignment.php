<?php

namespace App\Models;

class ImageAssignment extends BaseModel
{
    protected $fillable = [
        'image_id',
        'number'
    ];

    protected $guarded = [
        'id',
        'imageable_type',
        'imageable_id',
        'image_type_id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'imageable_type',
        'imageable_id',
        'image_type_id',
        'image_id',
        'number',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'number' => 'int',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function imageable() {
        return $this->morphTo();
    }

    public function imageType() {
        return $this->belongsTo(DefaultType::class, 'image_type_id');
    }

    public function image() {
        return $this->belongsTo(Image::class, 'image_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }
}
