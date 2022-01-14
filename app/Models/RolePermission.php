<?php

namespace App\Models;

class RolePermission extends BaseModel
{
    protected $fillable = [
        'role_id',
        'permission_id'
    ];

    protected $guarded = [
        'id',
        'creator_id',
        'created_at'
    ];

    protected $hidden = [
        'id',
        'role_id',
        'permission_id',
        'creator_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'string'
    ];

    public $timestamps = false;

    public function role() {
        return $this->belongsTo(DefaultType::class, 'role_id');
    }

    public function permission() {
        return $this->belongsTo(DefaultType::class, 'permission_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }
}
