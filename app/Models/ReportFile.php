<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class ReportFile extends BaseModel
{
    use Encryptable;

    protected $guarded = [
        'id',
        'report_id',
        'filename',
        'created_at'
    ];

    protected $hidden = [
        'id',
        'report_id',
        'filename',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'string'
    ];

    protected $encryptable = [
        'filename' => 48
    ];

    public function report() {
        return $this->belongsTo(Report::class);
    }
}
