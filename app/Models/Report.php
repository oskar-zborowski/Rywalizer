<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Report extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'reportable_type',
        'reportable_id',
        'email',
        'message'
    ];

    protected $guarded = [
        'id',
        'user_id',
        'supervisor_id',
        'report_status_id',
        'deadline_at',
        'fixed_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'reportable_type',
        'reportable_id',
        'email',
        'user_id',
        'supervisor_id',
        'message',
        'report_status_id',
        'deadline_at',
        'fixed_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'deadline_at' => 'string',
        'fixed_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'email' => 254,
        'message' => 4500
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function reportStatus() {
        return $this->belongsTo(DefaultType::class, 'report_status_id');
    }

    public function reportFiles() {
        return $this->belongsTo(ReportFile::class);
    }
}
