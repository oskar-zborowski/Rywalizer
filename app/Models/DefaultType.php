<?php

namespace App\Models;

class DefaultType extends BaseModel
{
    protected $fillable = [
        'default_type_name_id',
        'name',
        'description_simple',
        'description_perfect',
        'description_future',
        'icon_id',
        'is_active'
    ];

    protected $guarded = [
        'id',
        'creator_id',
        'editor_id',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'id',
        'default_type_name_id',
        'name',
        'description_simple',
        'description_perfect',
        'description_future',
        'icon_id',
        'creator_id',
        'editor_id',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public function defaultTypeName() {
        return $this->belongsTo(DefaultTypeName::class);
    }

    public function icon() {
        return $this->belongsTo(Icon::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function editor() {
        return $this->belongsTo(User::class, 'editor_id');
    }

    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function reportable() {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function userGenders() {
        return $this->hasMany(User::class, 'gender_id');
    }

    public function userRoles() {
        return $this->hasMany(User::class, 'role_id');
    }

    public function accountActionType() {
        return $this->hasMany(AccountActionType::class, 'account_action_type_id');
    }

    public function imagesAssignment() {
        return $this->hasMany(ImageAssignment::class, 'image_type_id');
    }

    public function registeredGuestsActions() {
        return $this->hasMany(RegisteredGuestAction::class, 'action_type_id');
    }

    public function accountsOperations() {
        return $this->hasMany(AccountOperation::class, 'account_operation_type_id');
    }

    public function areas() {
        return $this->hasMany(Area::class, 'area_type_id');
    }

    public function authentications() {
        return $this->hasMany(Authentication::class, 'authentication_type_id');
    }

    public function externalAuthentications() {
        return $this->hasMany(ExternalAuthentication::class, 'provider_id');
    }

    public function rolePermissionsByRole() {
        return $this->hasMany(RolePermission::class, 'role_id');
    }

    public function rolePermissionsByPermission() {
        return $this->hasMany(RolePermission::class, 'permission_id');
    }

    public function partnersSettingsByPartnerType() {
        return $this->hasMany(PartnerSetting::class, 'partner_type_id');
    }

    public function partnersSettingsByVisibleName() {
        return $this->hasMany(PartnerSetting::class, 'visible_name_id');
    }

    public function partnersSettingsByVisibleImage() {
        return $this->hasMany(PartnerSetting::class, 'visible_image_id');
    }

    public function partnersSettingsByVisibleEmail() {
        return $this->hasMany(PartnerSetting::class, 'visible_email_id');
    }

    public function partnersSettingsByVisibleTelephone() {
        return $this->hasMany(PartnerSetting::class, 'visible_telephone_id');
    }

    public function partnersSettingsByVisibleFacebook() {
        return $this->hasMany(PartnerSetting::class, 'visible_facebook_id');
    }

    public function partnersSettingsByVisibleInstagram() {
        return $this->hasMany(PartnerSetting::class, 'visible_instagram_id');
    }

    public function partnersSettingsByVisibleWebsite() {
        return $this->hasMany(PartnerSetting::class, 'visible_website_id');
    }

    public function discountCodesByDiscountType() {
        return $this->hasMany(PartnerSetting::class, 'discount_type_id');
    }

    public function discountCodesByDiscountValueType() {
        return $this->hasMany(PartnerSetting::class, 'discount_value_type_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, 'transaction_status_id');
    }

    public function facilitiesByFacilityType() {
        return $this->hasMany(Facility::class, 'facility_type_id');
    }

    public function facilitiesByGender() {
        return $this->hasMany(Facility::class, 'gender_id');
    }

    public function facilitiesByAgeCategory() {
        return $this->hasMany(Facility::class, 'age_category_id');
    }

    public function facilitiesAvailableSports() {
        return $this->hasMany(FacilityAvailableSport::class, 'sport_id');
    }

    public function facilitiesEquipment() {
        return $this->hasMany(FacilityEquipment::class, 'equipment_id');
    }

    public function facilitiesPlaces() {
        return $this->hasMany(FacilityPlace::class, 'facility_place_type_id');
    }

    public function facilitiesPlacesBookings() {
        return $this->hasMany(FacilityPlaceBooking::class, 'booking_status_id');
    }

    public function minimumSkillLevels() {
        return $this->hasMany(MinimumSkillLevel::class, 'sport_id');
    }

    public function sportsPositions() {
        return $this->hasMany(SportsPosition::class, 'sport_id');
    }

    public function announcementsBySport() {
        return $this->hasMany(Announcement::class, 'sport_id');
    }

    public function announcementsByGameVariant() {
        return $this->hasMany(Announcement::class, 'game_variant_id');
    }

    public function announcementsByGender() {
        return $this->hasMany(Announcement::class, 'gender_id');
    }

    public function announcementsByAgeCategory() {
        return $this->hasMany(Announcement::class, 'age_category_id');
    }

    public function announcementsByAgeAnnouncementType() {
        return $this->hasMany(Announcement::class, 'announcement_type_id');
    }

    public function announcementsByAgeAnnouncementStatus() {
        return $this->hasMany(Announcement::class, 'announcement_status_id');
    }

    public function announcementsPayments() {
        return $this->hasMany(AnnouncementPayment::class, 'payment_type_id');
    }

    public function announcementsParticipants() {
        return $this->hasMany(AnnouncementParticipant::class, 'joining_status_id');
    }

    public function agreements() {
        return $this->hasMany(Agreement::class, 'agreement_type_id');
    }

    public function reports() {
        return $this->hasMany(Report::class, 'report_status_id');
    }
}
