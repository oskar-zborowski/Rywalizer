<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Responses\JsonResponse;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Zwrócenie podstawowych informacji o partnerze
     * 
     * @return array
     */
    public function getBasicInformation($announcement = null): array {

        $visibleName = $this->visible_name_id;
        $visibleImage = $this->visible_image_id;
        $visibleEmail = $this->visible_email_id;
        $visibleTelephone = $this->visible_telephone_id;
        $visibleFacebook = $this->visible_facebook_id;
        $visibleInstagram = $this->visible_instagram_id;
        $visibleWebsite = $this->visible_website_id;

        /** @var User $loggedUser */
        $loggedUser = Auth::user();

        /** @var Partner $partnerByUser */
        if ($loggedUser) {
            $partnerByUser = $loggedUser->partners()->first();
        } else {
            $partnerByUser = null;
        }

        if ($partnerByUser) {
            /** @var PartnerSetting $partnerSettingByUser */
            $partnerSettingByUser = $partnerByUser->partnerSettings()->first();
        }

        /** @var Partner $partner */
        $partner = $this->partner()->first();

        /** @var User $user */
        $user = $partner->user()->first();

        if ($visibleName == 61) {
            $name = $user->first_name . ' ' . $user->last_name;
        } else if ($visibleName == 62) {
            $name = $partner->business_name;
        } else {
            $name = '';
        }

        if ($visibleImage == 61) {
            $images = $user->getAvatars(false);
        } else if ($visibleImage == 62) {
            $images = $partner->getLogos(false);
        } else {
            $images = [];
        }

        if ($visibleEmail == 61) {
            $email = $user->email;
        } else if ($visibleEmail == 62) {
            $email = $partner->contact_email;
        } else {
            $email = '';
        }

        if ($visibleTelephone == 61) {
            $telephone = $user->telephone;
        } else if ($visibleTelephone == 62) {
            $telephone = $partner->telephone;
        } else {
            $telephone = '';
        }

        if ($visibleFacebook == 61) {
            $facebook = $user->facebook_profile;
        } else if ($visibleFacebook == 62) {
            $facebook = $partner->facebook;
        } else {
            $facebook = '';
        }

        if ($visibleInstagram == 61) {
            $instagram = $user->instagram_profile;
        } else if ($visibleInstagram == 62) {
            $instagram = $partner->instagram;
        } else {
            $instagram = '';
        }

        if ($visibleWebsite == 61) {
            $website = $user->website;
        } else if ($visibleWebsite == 62) {
            $website = $partner->website;
        } else {
            $website = '';
        }

        return [
            'partner' => [
                'id' => (int) $partner->id,
                'alias' => $partner->alias,
                'name' => $name,
                'logos' => $images,
                'contact_email' => $email,
                'telephone' => $telephone,
                'facebook' => $facebook,
                'instagram' => $instagram,
                'website' => $website,
                'verified' => (bool) $partner->verified_at,
                'avarage_rating' => (float) $partner->avarage_rating,
                'rating_counter' => (int) $partner->rating_counter,
                'its_me' => isset($partnerSettingByUser) && $partnerSettingByUser && $partnerSettingByUser->id == $announcement->announcement_partner_id,
            ],
            'partnerSetting' => [
                'id' => (int) $this->id,
                'partner_type' => [
                    'id' => (int) $this->partnerType()->first()->id,
                    'name' => $this->partnerType()->first()->name
                ]
            ]
        ];
    }

    /**
     * Zwrócenie prywatnych informacji o partnerze
     * 
     * @return array
     */
    public function getPrivateInformation(): array {

        /** @var Partner $partner */
        $partner = $this->partner()->first();

        return [
            'partner' => [
                'id' => (int) $partner->id,
                'business_name' => $partner->business_name,
                'alias' => $partner->alias,
                'logos' => $partner->getLogos(false),
                'contact_email' => $partner->contact_email,
                'telephone' => $partner->telephone,
                'facebook' => $partner->facebook,
                'instagram' => $partner->instagram,
                'website' => $partner->website,
                'verified' => (bool) $partner->verified_at
            ],
            'partnerSetting' => [
                'id' => (int) $this->id,
                'partner_type' => [
                    'id' => (int) $this->partnerType()->first()->id,
                    'name' => $this->partnerType()->first()->name
                ],
                'visible_name' => [
                    'id' => (int) $this->visibleName()->first()->id,
                    'name' => $this->visibleName()->first()->name
                ],
                'visible_image' => [
                    'id' => (int) $this->visibleImage()->first()->id,
                    'name' => $this->visibleImage()->first()->name
                ],
                'visible_email' => [
                    'id' => (int) $this->visibleEmail()->first()->id,
                    'name' => $this->visibleEmail()->first()->name
                ],
                'visible_telephone' => [
                    'id' => (int) $this->visibleTelephone()->first()->id,
                    'name' => $this->visibleTelephone()->first()->name
                ],
                'visible_facebook' => [
                    'id' => (int) $this->visibleFacebook()->first()->id,
                    'name' => $this->visibleFacebook()->first()->name
                ],
                'visible_instagram' => [
                    'id' => (int) $this->visibleInstagram()->first()->id,
                    'name' => $this->visibleInstagram()->first()->name
                ],
                'visible_website' => [
                    'id' => (int) $this->visibleWebsite()->first()->id,
                    'name' => $this->visibleWebsite()->first()->name
                ]
            ]
        ];
    }

    /**
     * Zwrócenie informacji o partnerze
     * 
     * @param string $modelMethodName nazwa metody, która ma zostać dołączona jako wykaz zwróconych pól partnera, np. getPrivateInformation
     */
    public function getPartner($modelMethodName, $withReturn = false, $announcement = null) {

        /** @var Partner $partner */
        $partner = $this->partner()->first();

        if (!$partner->deleted_at) {
            if ($withReturn) {
                return $this->$modelMethodName($announcement);
            } else {
                JsonResponse::sendSuccess($this->$modelMethodName());
            }
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner nie istnieje.'
            );
        }
    }
}
