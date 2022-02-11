<?php

namespace App\Models;

use App\Http\Traits\Encryptable;

class Facility extends BaseModel
{
    use Encryptable;

    protected $fillable = [
        'name',
        'street',
        'post_code',
        'city_id',
        'address_coordinates',
        'contact_email',
        'telephone',
        'facebook_profile',
        'instagram_profile',
        'website',
        'facility_partner_id',
        'facility_type_id',
        'places_number',
        'gender_id',
        'age_category_id',
        'minimal_age',
        'maximum_age',
        'description'
    ];

    protected $guarded = [
        'id',
        'price_from',
        'occupancy_level',
        'avarage_rating',
        'rating_counter',
        'creator_id',
        'editor_id',
        'supervisor_id',
        'contact_email_verified_at',
        'telephone_verified_at',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'name',
        'street',
        'post_code',
        'city_id',
        'address_coordinates',
        'contact_email',
        'telephone',
        'facebook_profile',
        'instagram_profile',
        'website',
        'facility_partner_id',
        'facility_type_id',
        'places_number',
        'gender_id',
        'age_category_id',
        'minimal_age',
        'maximum_age',
        'description',
        'price_from',
        'occupancy_level',
        'avarage_rating',
        'rating_counter',
        'creator_id',
        'editor_id',
        'supervisor_id',
        'contact_email_verified_at',
        'telephone_verified_at',
        'visible_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'places_number' => 'int',
        'minimal_age' => 'int',
        'maximum_age' => 'int',
        'price_from' => 'int',
        'occupancy_level' => 'float',
        'avarage_rating' => 'float',
        'rating_counter' => 'int',
        'contact_email_verified_at' => 'string',
        'telephone_verified_at' => 'string',
        'visible_at' => 'string',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    protected $encryptable = [
        'address_coordinates' => 21
    ];

    public function city() {
        return $this->belongsTo(Area::class, 'city_id');
    }

    public function facilityPartner() {
        return $this->belongsTo(PartnerSetting::class, 'facility_partner_id');
    }

    public function facilityType() {
        return $this->belongsTo(DefaultType::class, 'facility_type_id');
    }

    public function gender() {
        return $this->belongsTo(DefaultType::class, 'gender_id');
    }

    public function ageCategory() {
        return $this->belongsTo(DefaultType::class, 'age_category_id');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function supervisor() {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function imageAssignments() {
        return $this->morphMany(ImageAssignment::class, 'imageable');
    }

    public function actionables() {
        return $this->morphMany(AccountAction::class, 'actionable');
    }

    public function operationable() {
        return $this->morphMany(AccountOperation::class, 'operationable');
    }

    public function evaluable() {
        return $this->morphMany(Rating::class, 'evaluable');
    }

    public function contractable() {
        return $this->morphMany(Agreement::class, 'contractable');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function facilityAvailableSports() {
        return $this->hasMany(FacilityAvailableSport::class);
    }

    public function facilityEquipments() {
        return $this->hasMany(FacilityEquipment::class);
    }

    public function facilityOpeningHours() {
        return $this->hasMany(FacilityOpeningHour::class);
    }

    public function facilityPlaces() {
        return $this->hasMany(FacilityPlace::class);
    }

    public function facilitySpecialOpeningHours() {
        return $this->hasMany(FacilitySpecialOpeningHour::class);
    }

    public function announcements() {
        return $this->hasMany(Announcement::class);
    }
}
