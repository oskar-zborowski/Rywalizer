<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class DiscountCode extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'code',
        'description',
        'discount_type_id',
        'discount_value_type_id',
        'value',
        'start_date',
        'end_date',
        'is_active',
        'is_visible'
    ];

    protected $guarded = [
        'id',
        'payer_id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'code',
        'description',
        'discount_type_id',
        'discount_value_type_id',
        'value',
        'start_date',
        'end_date',
        'payer_id',
        'creator_id',
        'editor_id',
        'is_active',
        'is_visible',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'value' => 'int',
        'start_date' => 'string',
        'end_date' => 'string',
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'code' => 30
    ];

    public function discountType() {
        return $this->belongsTo(DefaultType::class, 'discount_type_id');
    }

    public function discountValueType() {
        return $this->belongsTo(DefaultType::class, 'discount_value_type_id');
    }

    public function payer() {
        return $this->belongsTo(Partner::class, 'payer_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function imageAssignments() {
        return $this->morphMany(ImageAssignment::class, 'imageable');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function discounts() {
        return $this->hasMany(Discount::class, 'discount_code_id');
    }
}
