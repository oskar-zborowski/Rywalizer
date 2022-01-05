<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Announcement extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'announcement_partner_id',
        'facility_id',
        'sport_id',
        'start_date',
        'end_date',
        'visible_at',
        'ticket_price',
        'game_variant_id',
        'minimum_skill_level_id',
        'gender_id',
        'age_category_id',
        'minimal_age',
        'maximum_age',
        'description',
        'maximum_participants_number',
        'announcement_type_id',
        'announcement_status_id',
        'is_automatically_approved',
        'is_public'
    ];

    protected $guarded = [
        'id',
        'code',
        'participants_counter',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'announcement_partner_id',
        'facility_id',
        'sport_id',
        'start_date',
        'end_date',
        'visible_at',
        'ticket_price',
        'game_variant_id',
        'minimum_skill_level_id',
        'gender_id',
        'age_category_id',
        'minimal_age',
        'maximum_age',
        'code',
        'description',
        'participants_counter',
        'maximum_participants_number',
        'announcement_type_id',
        'announcement_status_id',
        'creator_id',
        'editor_id',
        'is_automatically_approved',
        'is_public',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'start_date' => 'string',
        'end_date' => 'string',
        'visible_at' => 'string',
        'ticket_price' => 'int',
        'minimal_age' => 'int',
        'maximum_age' => 'int',
        'participants_counter' => 'int',
        'maximum_participants_number' => 'int',
        'is_automatically_approved' => 'boolean',
        'is_public' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'code' => 8,
        'description' => 1500
    ];

    public function announcementPartner() {
        return $this->belongsTo(PartnerSetting::class, 'announcement_partner_id');
    }

    public function facility() {
        return $this->belongsTo(Facility::class);
    }

    public function sport() {
        return $this->belongsTo(DefaultType::class, 'sport_id');
    }

    public function gameVariant() {
        return $this->belongsTo(DefaultType::class, 'game_variant_id');
    }

    public function minimumSkillLevel() {
        return $this->belongsTo(MinimumSkillLevel::class);
    }

    public function gender() {
        return $this->belongsTo(DefaultType::class, 'gender_id');
    }

    public function ageCategory() {
        return $this->belongsTo(DefaultType::class, 'age_category_id');
    }

    public function announcementType() {
        return $this->belongsTo(DefaultType::class, 'announcement_type_id');
    }

    public function announcementStatus() {
        return $this->belongsTo(DefaultType::class, 'announcement_status_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function announcementPayments() {
        return $this->hasMany(AnnouncementPayment::class);
    }

    public function announcementSeats() {
        return $this->hasMany(AnnouncementSeat::class);
    }
}
