<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Http\Requests\AnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Http\Responses\JsonResponse;
use App\Models\Announcement;
use App\Models\AnnouncementPayment;
use App\Models\AnnouncementSeat;
use App\Models\Facility;
use App\Models\User;
use App\Models\Partner;
use App\Models\PartnerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * #### `POST` `/api/v1/announcement`
     * Utworzenie nowego ogłoszenia
     * 
     * @param AnnouncementRequest $request
     * 
     * @return void
     */
    public function createAnnouncement(AnnouncementRequest $request): void {

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partnerExists */
        $partnerExists = $user->partners()->first();

        if ($partnerExists) {
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partnerExists->partnerSettings()->first();
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }

        if ($partnerSetting->partner_type_id = 59) {

            /** @var Announcement $announcement */
            $announcement = new Announcement;
            $announcement->announcement_partner_id = $partnerSetting->id;

            if ($request->facility_id) {
                $announcement->facility_id = $request->facility_id;
            } else if ($request->facility_name || $request->facility_street || $request->city_id || $request->city_name) {
                if ($request->city_id || $request->city_name) {
                    $city = Validation::createArea($request);
                }

                $facility = new Facility;
                $facility->name = $request->facility_name;
                $facility->street = $request->facility_street;

                if (isset($city) && $city) {
                    $facility->city_id = $city->id;
                }

                $facility->save;
                $announcement->facility_id = $facility->id;
            }

            $encrypter = new Encrypter;
            $code = $encrypter->generateToken(12, Announcement::class, 'code', '', true);

            $maximumParticipantsNumber = 0;

            foreach ($request->sport_positions as $sP) {
                $maximumParticipantsNumber += $sP['maximum_seats_number'];
            }

            $announcement->sport_id = $request->sport_id;
            $announcement->start_date = $request->start_date;
            $announcement->end_date = $request->end_date;
            $announcement->visible_at = $request->visible_at;
            $announcement->ticket_price = $request->ticket_price;
            $announcement->game_variant_id = $request->game_variant_id;
            $announcement->minimum_skill_level_id = $request->minimum_skill_level_id;
            $announcement->gender_id = $request->gender_id;
            $announcement->age_category_id = $request->age_category_id;
            $announcement->minimal_age = $request->minimal_age;
            $announcement->maximum_age = $request->maximum_age;
            $announcement->code = $code;
            $announcement->description = $request->description;
            $announcement->maximum_participants_number = $maximumParticipantsNumber;
            $announcement->announcement_type_id = $request->announcement_type_id;
            $announcement->announcement_status_id = 85;
            $announcement->creator_id = $user->id;
            $announcement->editor_id = $user->id;
            $announcement->is_automatically_approved = $request->is_automatically_approved;
            $announcement->is_public = $request->is_public;
            $announcement->save();

            foreach ($request->sport_positions as $sP) {
                /** @var AnnouncementSeat $announcementSeat */
                $announcementSeat = new AnnouncementSeat;
                $announcementSeat->announcement_id = $announcement->id;
                $announcementSeat->sports_position_id = $sP['sport_position_id'];
                $announcementSeat->maximum_seats_number	= $sP['maximum_seats_number'];
                $announcementSeat->creator_id = $user->id;
                $announcementSeat->editor_id = $user->id;
                $announcementSeat->save();
            }

            foreach ($request->payment_type_ids as $pTI) {
                /** @var AnnouncementPayment $announcementPayment */
                $announcementPayment = new AnnouncementPayment;
                $announcementPayment->announcement_id = $announcement->id;
                $announcementPayment->payment_type_id = $pTI;
                $announcementPayment->creator_id = $user->id;
                $announcementPayment->editor_id = $user->id;
                $announcementPayment->save();
            }

            $announcement->getAnnouncement('getBasicInformation');

        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }
    }

    /**
     * #### `PATCH` `/api/v1/announcement/{id}`
     * Edycja danych ogłoszenia
     * 
     * @param int $id id ogłoszenia
     * @param UpdateAnnouncementRequest $request
     * 
     * @return void
     */
    public function updateAnnouncement($id, UpdateAnnouncementRequest $request): void {

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partnerExists */
        $partnerExists = $user->partners()->first();

        if ($partnerExists) {
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partnerExists->partnerSettings()->first();
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }

        /** @var Announcement $announcement */
        $announcement = Announcement::where('id', $id)->first();

        if ($partnerSetting->partner_type_id = 59 && $announcement->announcement_partner_id == $partnerSetting->id) {

            if ($request->facility_id) {
                $announcement->facility_id = $request->facility_id;
            } else if ($request->facility_name || $request->facility_street || $request->city_id || $request->city_name) {
                if ($request->city_id || $request->city_name) {
                    $city = Validation::createArea($request);
                }

                $facility = new Facility;
                $facility->name = $request->facility_name;
                $facility->street = $request->facility_street;

                if (isset($city) && $city) {
                    $facility->city_id = $city->id;
                }

                $facility->save;
                $announcement->facility_id = $facility->id;
            }

            $announcement->start_date = $request->start_date;
            $announcement->end_date = $request->end_date;
            $announcement->visible_at = $request->visible_at;
            $announcement->ticket_price = $request->ticket_price;
            $announcement->minimum_skill_level_id = $request->minimum_skill_level_id;
            $announcement->gender_id = $request->gender_id;
            $announcement->age_category_id = $request->age_category_id;
            $announcement->minimal_age = $request->minimal_age;
            $announcement->maximum_age = $request->maximum_age;
            $announcement->description = $request->description;
            $announcement->announcement_status_id = 85;
            $announcement->editor_id = $user->id;
            $announcement->is_automatically_approved = $request->is_automatically_approved;
            $announcement->is_public = $request->is_public;
            $announcement->save();

            $announcement->getAnnouncement('getBasicInformation');

        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Permission denied.'
            );
        }
    }

    /**
     * #### `GET` `/api/v1/announcement/{id}`
     * Pobranie informacji o wydarzeniu
     * 
     * @return void
     */
    public function getAnnouncementById($id): void {
        /** @var Announcement $announcement */
        $announcement = Announcement::where('id', $id)->first();

        if ($announcement) {
            $announcement->getAnnouncement('getBasicInformation');
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Announcement does not exist.'
            );
        }
    }

    /**
     * #### `POST` `/api/v1/announcement/{id}/photo`
     * Wgranie zdjęcia w tle
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function uploadPhoto($id, Request $request): void {

        $request->validate([
            'photo' => 'image|max:2048'
        ]);

        if (!$request->photo) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Missing photo.'
            );
        }

        /** @var Announcement $announcement */
        $announcement = Announcement::where('id', $id)->first();

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        if ($partner && $announcement) {
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();

            if ($announcement->announcement_partner_id != $partnerSetting->id) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    'Partner does not exist.'
                );
            }
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }
        
        $announcement->savePhoto($request->photo);
        $announcement->getAnnouncement('getBasicInformation');
    }

    /**
     * #### `DELETE` `/api/v1/announcement/{id}/photo/{id}`
     * Usunięcie zdjęcia w tle dla wydarzenia
     * 
     * @param int $id id wydarzenia
     * @param int $photoId id zdjęcia
     * 
     * @return void
     */
    public function deletePhoto(int $id, int $photoId): void {

        /** @var User $user */
        $user = Auth::user();

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        /** @var Announcement $announcement */
        $announcement = Announcement::where('id', $id)->first();

        if ($partner && $announcement) {
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();

            if ($partnerSetting->id == $announcement->announcement_partner_id) {
                $announcement->deletePhoto($photoId);
                JsonResponse::sendSuccess();
            } else {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    'Photo does not exist.'
                );
            }
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Photo does not exist.'
            );
        }
    }

    /**
     * #### `GET` `/api/v1/announcements`
     * Pobranie listy wydarzeń
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function getAnnouncements(Request $request): void {
        $paginationAttributes = $this->getPaginationAttributes($request);

        /** @var Announcement $announcements */
        $announcements = Announcement::where('visible_at', '<=', now())->where('start_date', '>', now())->filter()->paginate($paginationAttributes['perPage']);

        $result = $this->preparePagination($announcements, 'getBasicInformation');

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
    }
}
