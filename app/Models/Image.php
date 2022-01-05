<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Image extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'imageable_type',
        'imageable_id'
    ];

    protected $guarded = [
        'id',
        'filename',
        'creator_id',
        'supervisor_id',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'imageable_type',
        'imageable_id',
        'filename',
        'creator_id',
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

    protected $encryptable = [
        'filename' => 64
    ];

    public function imageable() {
        return $this->morphTo();
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function imageAssignment() {
        return $this->hasMany(ImageAssignment::class);
    }
}
