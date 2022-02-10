<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Requests\PartnerRequest;
use App\Http\Responses\JsonResponse;
use App\Models\Partner;
use App\Models\PartnerSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PartnerController extends Controller
{
    /**
     * #### `POST` `/api/v1/partners`
     * Utworzenie nowego partnera
     * 
     * @param PartnerRequest $request
     * 
     * @return void
     */
    public function createPartner(PartnerRequest $request): void {

        if ($request->telephone) {

            $telephoneLength = strlen($request->telephone);

            for ($i=0; $i<$telephoneLength; $i++) {
                if (!is_numeric($request->telephone[$i])) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['telephone' => [__('validation.regex')]]
                    );
                }
            }
        }

        /** @var User $user */
        $user = Auth::user();

        $partnerExists = $user->partners()->first();

        if (!$partnerExists) {
            $partner = new Partner;
            $partner->user_id = $user->id;
            $partner->first_name = $user->first_name;
            $partner->last_name = $user->last_name;
            $partner->business_name = $request->business_name;
            $partner->contact_email = $request->contact_email;
            $partner->telephone = $request->telephone;
            $partner->facebook_profile = $request->facebook_profile;
            $partner->instagram_profile = $request->instagram_profile;
            $partner->website = $request->website;
            $partner->creator_id = $user->id;
            $partner->editor_id = $user->id;
            $partner->save();
    
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = new PartnerSetting();
            $partnerSetting->partner_id = $partner->id;
            $partnerSetting->commission_id = 1;
            $partnerSetting->partner_type_id = 59;
            $partnerSetting->visible_name_id = $request->visible_name_id;
            $partnerSetting->visible_image_id = $request->visible_image_id;
            $partnerSetting->visible_email_id = $request->visible_email_id;
            $partnerSetting->visible_telephone_id = $request->visible_telephone_id;
            $partnerSetting->visible_facebook_id = $request->visible_facebook_id;
            $partnerSetting->visible_instagram_id = $request->visible_instagram_id;
            $partnerSetting->visible_website_id = $request->visible_website_id;
            $partnerSetting->creator_id = $user->id;
            $partnerSetting->editor_id = $user->id;
            $partnerSetting->save();

            $partnerSetting->getPartner('getPrivateInformation');
        } else {
            /** @var Partner $partner */
            $partner = $user->partners()->first();

            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
            $partnerSetting->getPartner('getPrivateInformation');
        }
    }

    /**
     * #### `PATCH` `/api/v1/partners`
     * Edycja danych partnera
     * 
     * @param PartnerRequest $request
     * 
     * @return void
     */
    public function updatePartner(PartnerRequest $request): void {

        if ($request->telephone) {

            $telephoneLength = strlen($request->telephone);

            for ($i=0; $i<$telephoneLength; $i++) {
                if (!is_numeric($request->telephone[$i])) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['telephone' => [__('validation.regex')]]
                    );
                }
            }
        }

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        if ($partner) {
            $partner->first_name = $user->first_name;
            $partner->last_name = $user->last_name;
            $partner->business_name = $request->business_name;
            $partner->contact_email = $request->contact_email;
            $partner->telephone = $request->telephone;
            $partner->facebook_profile = $request->facebook_profile;
            $partner->instagram_profile = $request->instagram_profile;
            $partner->website = $request->website;
            $partner->editor_id = $user->id;
            $partner->save();
    
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
            $partnerSetting->visible_name_id = $request->visible_name_id;
            $partnerSetting->visible_image_id = $request->visible_image_id;
            $partnerSetting->visible_email_id = $request->visible_email_id;
            $partnerSetting->visible_telephone_id = $request->visible_telephone_id;
            $partnerSetting->visible_facebook_id = $request->visible_facebook_id;
            $partnerSetting->visible_instagram_id = $request->visible_instagram_id;
            $partnerSetting->visible_website_id = $request->visible_website_id;
            $partnerSetting->editor_id = $user->id;
            $partnerSetting->save();
    
            $partnerSetting->getPartner('getPrivateInformation');
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }
    }

    /**
     * #### `GET` `/api/v1/partners`
     * Pobranie prywatnych informacji o partnerze
     * 
     * @return void
     */
    public function getPartner(): void {
        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        if ($partner) {
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
            $partnerSetting->getPartner('getPrivateInformation');
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }
    }

    /**
     * #### `GET` `/api/v1/partners/{id}`
     * Pobranie podstawowych informacji o partnerze
     * 
     * @return void
     */
    public function getPartnerById($id): void {

        /** @var Partner $partner */
        $partner = Partner::where('id', $id)->first();

        if ($partner) {
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
            $partnerSetting->getPartner('getBasicInformation');
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }
    }

    /**
     * #### `DELETE` `/api/v1/partners`
     * Usunięcie partnera
     * 
     * @return void
     */
    public function deletePartner(): void {

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        if ($partner && !$partner->deleted_at) {
            $partner->deleted_at = now();
            $partner->save();
            JsonResponse::sendSuccess();
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }
    }

    /**
     * #### `POST` `/api/v1/partners/logo`
     * Wgranie loga partnera
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function uploadLogo(Request $request): void {

        $request->validate([
            'logo' => 'image|max:2048'
        ]);

        if (!$request->logo) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Missing logo image.'
            );
        }

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        if ($partner) {
            $partner->saveLogo($request->logo);

            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
            $partnerSetting->getPartner('getPrivateInformation');
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Logo does not exist.'
            );
        }
    }

    /**
     * #### `PUT` `/api/v1/partners/logo`
     * Zmiana loga partnera
     * 
     * @param int $id id loga
     * 
     * @return void
     */
    public function changeLogo(int $id): void {

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        if ($partner) {
            $partner->changeLogo($id);

            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
            $partnerSetting->getPartner('getPrivateInformation');
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Logo does not exist.'
            );
        }
    }

    /**
     * #### `DELETE` `/api/v1/partners/logo`
     * Usunięcie loga partnera
     * 
     * @param int $id id loga
     * 
     * @return void
     */
    public function deleteLogo(int $id): void {

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        if ($partner) {
            $partner->deleteLogo($id);

            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
            $partnerSetting->getPartner('getPrivateInformation');
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Logo does not exist.'
            );
        }
    }
}
