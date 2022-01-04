<?php

namespace App\Models;

class PartnerSetting extends BaseModel
{
    protected $fillable = [
        'visible_name_id',
        'visible_image_id',
        'visible_email_id',
        'visible_telephone_id',
        'visible_facebook_id',
        'visible_instagram_id',
        'visible_website_id'
    ];

    protected $guarded = [
        'id',
        'partner_id',
        'partner_type_id',
        'commission_id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'partner_id',
        'partner_type_id',
        'commission_id',
        'visible_name_id',
        'visible_image_id',
        'visible_email_id',
        'visible_telephone_id',
        'visible_facebook_id',
        'visible_instagram_id',
        'visible_website_id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function partner() {
        return $this->belongsTo(Partner::class);
    }

    public function partnerType() {
        return $this->belongsTo(DefaultType::class, 'partner_type_id');
    }

    public function commission() {
        return $this->belongsTo(DefaultType::class, 'commission_id');
    }

    public function visibleName() {
        return $this->belongsTo(DefaultType::class, 'visible_name_id');
    }

    public function visibleImage() {
        return $this->belongsTo(DefaultType::class, 'visible_image_id');
    }

    public function visibleEmail() {
        return $this->belongsTo(DefaultType::class, 'visible_email_id');
    }

    public function visibleTelephone() {
        return $this->belongsTo(DefaultType::class, 'visible_telephone_id');
    }

    public function visibleFacebook() {
        return $this->belongsTo(DefaultType::class, 'visible_facebook_id');
    }

    public function visibleInstagram() {
        return $this->belongsTo(DefaultType::class, 'visible_instagram_id');
    }

    public function visibleWebsite() {
        return $this->belongsTo(DefaultType::class, 'visible_website_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function facilities() {
        return $this->hasMany(Facility::class, 'facility_partner_id');
    }

    public function announcements() {
        return $this->hasMany(Announcement::class, 'announcement_partner_id');
    }
}
