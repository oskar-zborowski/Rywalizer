<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Agreement extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'description',
        'signature',
        'effective_date',
        'is_required',
        'is_visible'
    ];

    protected $guarded = [
        'id',
        'contractable_type',
        'contractable_id',
        'filename',
        'version',
        'agreement_type_id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'contractable_type',
        'contractable_id',
        'filename',
        'description',
        'signature',
        'version',
        'agreement_type_id',
        'effective_date',
        'creator_id',
        'editor_id',
        'is_required',
        'is_visible',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'version' => 'int',
        'effective_date' => 'string',
        'is_required' => 'boolean',
        'is_visible' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'filename' => 48,
        'description' => 100,
        'signature' => 30
    ];

    public function agreementType() {
        return $this->belongsTo(DefaultType::class, 'agreement_type_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function usersAgreements() {
        return $this->hasMany(UserAgreement::class);
    }
}
