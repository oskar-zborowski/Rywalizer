<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Http\Requests\AnnouncementRequest;
use App\Http\Responses\JsonResponse;
use App\Models\Announcement;
use App\Models\AnnouncementPayment;
use App\Models\AnnouncementSeat;
use App\Models\AnnouncementParticipant;
use App\Models\Facility;
use App\Models\User;
use App\Models\Partner;
use App\Models\PartnerSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnnouncementController extends Controller
{
    /**
     * #### `POST` `/api/v1/announcements`
     * Utworzenie nowego ogłoszenia
     * 
     * @param AnnouncementRequest $request
     * 
     * @return void
     */
    public function createAnnouncement(AnnouncementRequest $request): void {

        /** @var User $user */
        $user = Auth::user();

        if ($request->facility_address_coordinates) {

            $addressCoordinates = explode(';', $request->facility_address_coordinates);

            if (count($addressCoordinates) != 2) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    ['address_coordinates' => [__('validation.regex')]]
                );
            }

            $latitudeLength = strlen($addressCoordinates[0]);
            $longitudeLength = strlen($addressCoordinates[1]);

            if ($latitudeLength != 10 ||
                $longitudeLength != 10 ||
                $addressCoordinates[0][2] != '.' ||
                $addressCoordinates[1][2] != '.')
            {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    ['address_coordinates' => [__('validation.regex')]]
                );
            }

            for ($i=0; $i<$latitudeLength; $i++) {
                if ((!is_numeric($addressCoordinates[0][$i]) ||
                    !is_numeric($addressCoordinates[1][$i])) &&
                    $i != 2)
                {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['address_coordinates' => [__('validation.regex')]]
                    );
                }
            }
        }

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

        if ($request->start_date > $request->start_date) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'The end date cannot be earlier than the start date.'
            );
        }

        if ($request->start_date < now()) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'The start date cannot be in the past.'
            );
        }

        $maximumParticipantsNumber = 0;

        foreach ($request->sports_positions as $sP) {
            if (!isset($sP['maximum_seats_number']) || !isset($sP['sports_position_id'])) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    'Missing field of array.'
                );
            }
            $maximumParticipantsNumber += $sP['maximum_seats_number'];
        }

        if ($partnerSetting->partner_type_id = 59) {

            /** @var Announcement $announcement */
            $announcement = new Announcement;
            $announcement->announcement_partner_id = $partnerSetting->id;

            if ($request->facility_id) {
                $announcement->facility_id = $request->facility_id;
            } else if ($request->facility_name || $request->facility_street || $request->facility_address_coordinates || $request->city_id || $request->city_name) {
                if ($request->city_id || $request->city_name) {
                    $city = Validation::createArea($request);
                }

                $facility = new Facility;
                $facility->name = $request->facility_name;
                $facility->street = $request->facility_street;
                $facility->address_coordinates = $request->facility_address_coordinates;
                $facility->creator_id = $user->id;
                $facility->editor_id = $user->id;

                if (isset($city) && $city) {
                    $facility->city_id = $city->id;
                }

                $facility->save();
                $announcement->facility_id = $facility->id;
            }

            $result = [];

            foreach ($request->sports_positions as $a) {
                $keyExist = false;
                foreach ($result as &$r) {
                    if (isset($r['sports_position_id']) && $r['sports_position_id'] == $a['sports_position_id']) {
                        $r['maximum_seats_number'] += $a['maximum_seats_number'];
                        $keyExist = true;
                        break;
                    }
                }

                if (!$keyExist) {
                    $result[] = [
                        'sports_position_id' => $a['sports_position_id'],
                        'maximum_seats_number' => $a['maximum_seats_number']
                    ];
                }
            }
    
            if ($request->game_variant_id != $announcement->game_variant_id) {
                if ($request->game_variant_id == 77) {

                    $arrayKeyExists = false;

                    foreach ($result as $r) {
                        if ($r['sports_position_id'] == 6) {
                            $arrayKeyExists = true;
                        }
                    }

                    if (count($result) != 1 || !$arrayKeyExists) {
                        throw new ApiException(
                            BaseErrorCode::FAILED_VALIDATION(),
                            'Wrong game variant.'
                        );
                    }
                }

                $arrayKeyExists = false;

                foreach ($result as $r) {
                    if ($r['sports_position_id'] == 6) {
                        $arrayKeyExists = true;
                    }
                }
    
                if ($request->game_variant_id == 78 && $arrayKeyExists) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'Wrong game variant.'
                    );
                }
            }

            $encrypter = new Encrypter;
            $code = $encrypter->generateToken(12, Announcement::class, 'code', '', true);

            $announcement->sport_id = $request->sport_id;
            $announcement->start_date = $request->start_date;
            $announcement->end_date = $request->end_date;
            $announcement->visible_at = now();
            $announcement->ticket_price = $request->ticket_price;
            $announcement->game_variant_id = $request->game_variant_id;
            $announcement->minimum_skill_level_id = $request->minimum_skill_level_id;
            $announcement->gender_id = $request->gender_id;
            // $announcement->age_category_id = $request->age_category_id;
            // $announcement->minimal_age = $request->minimal_age;
            // $announcement->maximum_age = $request->maximum_age;
            $announcement->code = $code;
            $announcement->description = $request->description;
            $announcement->maximum_participants_number = $maximumParticipantsNumber;
            $announcement->announcement_type_id = 83;
            $announcement->announcement_status_id = 85;
            $announcement->creator_id = $user->id;
            $announcement->editor_id = $user->id;
            $announcement->is_automatically_approved = true;
            $announcement->is_public = $request->is_public;
            $announcement->save();

            foreach ($result as $sP) {
                /** @var AnnouncementSeat $announcementSeat */
                $announcementSeat = new AnnouncementSeat;
                $announcementSeat->announcement_id = $announcement->id;
                $announcementSeat->sports_position_id = $sP['sports_position_id'];
                $announcementSeat->maximum_seats_number	= $sP['maximum_seats_number'];
                $announcementSeat->creator_id = $user->id;
                $announcementSeat->editor_id = $user->id;
                $announcementSeat->save();
            }

            /** @var AnnouncementPayment $announcementPayment */
            $announcementPayment = new AnnouncementPayment;
            $announcementPayment->announcement_id = $announcement->id;
            $announcementPayment->payment_type_id = 87;
            $announcementPayment->creator_id = $user->id;
            $announcementPayment->editor_id = $user->id;
            $announcementPayment->save();

            $announcement->getAnnouncement('getBasicInformation');

        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Partner does not exist.'
            );
        }
    }

    /**
     * #### `PATCH` `/api/v1/announcements/{id}`
     * Edycja danych ogłoszenia
     * 
     * @param int $id id ogłoszenia
     * @param AnnouncementRequest $request
     * 
     * @return void
     */
    public function updateAnnouncement($id, AnnouncementRequest $request): void {

        $request->validate([
            'announcement_status_id' => 'required|integer|between:85,86',
        ]);

        if ($request->facility_address_coordinates) {

            $addressCoordinates = explode(';', $request->facility_address_coordinates);

            if (count($addressCoordinates) != 2) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    ['address_coordinates' => [__('validation.regex')]]
                );
            }

            $latitudeLength = strlen($addressCoordinates[0]);
            $longitudeLength = strlen($addressCoordinates[1]);

            if ($latitudeLength != 10 ||
                $longitudeLength != 10 ||
                $addressCoordinates[0][2] != '.' ||
                $addressCoordinates[1][2] != '.')
            {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    ['address_coordinates' => [__('validation.regex')]]
                );
            }

            for ($i=0; $i<$latitudeLength; $i++) {
                if ((!is_numeric($addressCoordinates[0][$i]) ||
                    !is_numeric($addressCoordinates[1][$i])) &&
                    $i != 2)
                {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['address_coordinates' => [__('validation.regex')]]
                    );
                }
            }
        }

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

        if ($request->start_date > $request->start_date) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'The end date cannot be earlier than the start date.'
            );
        }

        if ($request->start_date < now()) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'The start date cannot be in the past.'
            );
        }

        /** @var Announcement $announcement */
        $announcement = Announcement::where('id', $id)->first();

        if ($partnerSetting->partner_type_id = 59 && $announcement && $announcement->announcement_partner_id == $partnerSetting->id) {

            if ($request->facility_id) {
                $announcement->facility_id = $request->facility_id;
            } else if ($request->facility_name || $request->facility_street || $request->facility_address_coordinates || $request->city_id || $request->city_name) {
                if ($request->city_id || $request->city_name) {
                    $city = Validation::createArea($request);
                }

                $facility = new Facility;
                $facility->name = $request->facility_name;
                $facility->street = $request->facility_street;
                $facility->address_coordinates = $request->facility_address_coordinates;
                $facility->creator_id = $user->id;
                $facility->editor_id = $user->id;

                if (isset($city) && $city) {
                    $facility->city_id = $city->id;
                }

                $facility->save();
                $announcement->facility_id = $facility->id;
            }

            $maximumParticipantsNumber = 0;

            foreach ($request->sports_positions as $sP) {
                if (!isset($sP['maximum_seats_number']) || !isset($sP['sports_position_id'])) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'Missing field of array.'
                    );
                }
                $maximumParticipantsNumber += $sP['maximum_seats_number'];
            }

            $announcement->ticket_price = $request->ticket_price;
            $announcement->minimum_skill_level_id = $request->minimum_skill_level_id;
            $announcement->gender_id = $request->gender_id;
            // $announcement->age_category_id = $request->age_category_id;
            // $announcement->minimal_age = $request->minimal_age;
            // $announcement->maximum_age = $request->maximum_age;
            $announcement->description = $request->description;
            $announcement->announcement_status_id = $request->announcement_status_id;
            $announcement->editor_id = $user->id;
            $announcement->is_public = $request->is_public;
            $announcement->start_date = $request->start_date;
            $announcement->end_date = $request->end_date;

            /** @var AnnouncementPayment $announcementPayment */
            $announcementPayment = $announcement->announcementPayments()->first();

            /** @var AnnouncementParticipant $announcementParticipant */
            $announcementParticipants = $announcementPayment->announcementParticipants()->get();

            $sportPositions = [];

            /** @var AnnouncementParticipant $aP */
            foreach ($announcementParticipants as $aP) {
                /** @var AnnouncementSeat $announcementSeat */
                $announcementSeat = $aP->announcementSeat()->first();

                if (!array_key_exists($announcementSeat->sportsPosition()->first()->id, $sportPositions)) {
                    $sportPositions[$announcementSeat->sportsPosition()->first()->id] = 1;
                } else {
                    $sportPositions[$announcementSeat->sportsPosition()->first()->id]++;
                }
            }

            $result = [];

            foreach ($request->sports_positions as $a) {
                $keyExist = false;
                foreach ($result as $i => $r) {
                    if (isset($r['sports_position_id']) && $r['sports_position_id'] == $a['sports_position_id']) {
                        $result[$i]['maximum_seats_number'] += $a['maximum_seats_number'];
                        $keyExist = true;
                        break;
                    }
                }

                if (!$keyExist) {
                    $result[] = [
                        'sports_position_id' => $a['sports_position_id'],
                        'maximum_seats_number' => $a['maximum_seats_number']
                    ];
                }
            }

            foreach ($result as $sP) {
                if (isset($sportPositions[$sP['sports_position_id']]) && $sportPositions[$sP['sports_position_id']] > $sP['maximum_seats_number']) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'The number of players cannot be reduced as the sports positions are already taken.'
                    );
                }
            }

            foreach ($sportPositions as $k => $v) {
                $arrayKeyExists = false;
                foreach ($result as $r) {
                    if ($r['sports_position_id'] == $k) {
                        $arrayKeyExists = true;
                    }
                }
                if (!$arrayKeyExists) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'The number of players cannot be reduced as the sports positions are already taken.'
                    );
                }
            }

            if ($request->game_variant_id != $announcement->game_variant_id) {
                if ($request->game_variant_id == 77) {

                    $arrayKeyExists = false;

                    foreach ($result as $r) {
                        if ($r['sports_position_id'] == 6) {
                            $arrayKeyExists = true;
                        }
                    }

                    if (count($result) != 1 || !$arrayKeyExists) {
                        throw new ApiException(
                            BaseErrorCode::FAILED_VALIDATION(),
                            'Wrong game variant.'
                        );
                    }
                }

                $arrayKeyExists = false;

                foreach ($result as $r) {
                    if ($r['sports_position_id'] == 6) {
                        $arrayKeyExists = true;
                    }
                }
    
                if ($request->game_variant_id == 78 && $arrayKeyExists) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'Wrong game variant.'
                    );
                }
            }

            /** @var AnnouncementSeat $announcementSeats */
            $announcementSeats = $announcement->announcementSeats()->get();

            /** @var AnnouncementSeat $aS */
            foreach ($announcementSeats as $aS) {
                $arrayKeyExists = false;
                foreach ($result as $r) {
                    if ($r['sports_position_id'] == $aS->sports_position_id) {
                        $arrayKeyExists = true;
                    }
                }
                if (!$arrayKeyExists) {
                    $aS->delete();
                }
            }

            // echo json_encode($result);
            // die;

            foreach ($result as $sP) {

                /** @var AnnouncementSeat $announcementSeat */
                $announcementSeat = $announcement->announcementSeats()->where('sports_position_id', $sP['sports_position_id'])->first();

                if (!$announcementSeat) {
                    $announcementSeat = new AnnouncementSeat;
                    $announcementSeat->announcement_id = $announcement->id;
                    $announcementSeat->sports_position_id = $sP['sports_position_id'];
                    $announcementSeat->creator_id = $user->id;
                }

                $announcementSeat->maximum_seats_number	= $sP['maximum_seats_number'];
                $announcementSeat->editor_id = $user->id;
                $announcementSeat->save();
            }

            $announcement->game_variant_id = $request->game_variant_id;
            $announcement->maximum_participants_number = $maximumParticipantsNumber;
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
     * #### `GET` `/api/v1/announcements/{id}`
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
     * #### `POST` `/api/v1/announcements/{id}/photos`
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
     * #### `DELETE` `/api/v1/announcements/{id}/photos/{id}`
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
        $announcements = Announcement::where('visible_at', '<=', now())->filter()->paginate($paginationAttributes['perPage']);

        $result = $this->preparePagination($announcements, 'getBasicInformation');

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
    }

    /**
     * #### `POST` `/api/v1/announcements/join`
     * Zapisanie się na wydarzenie
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function joinToAnnouncement(Request $request): void {

        $request->validate([
            'announcement_id' => 'required|integer|exists:announcements,id',
            'announcement_seat_id' => 'required|integer|exists:announcement_seats,id'
        ]);

        /** @var User $user */
        $user = Auth::user();

        /** @var Announcement $announcement */
        $announcement = Announcement::where('id', $request->announcement_id)->where('start_date', '>=', now())->where('visible_at', '<=', now())->where('announcement_status_id', 85)->first();

        if (!$announcement) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Announcement does not exist.'
            );
        }

        /** @var AnnouncementSeat $announcementSeat */
        $announcementSeat = $announcement->announcementSeats()->where('id', $request->announcement_seat_id)->where('is_active', true)->first();

        if (!$announcementSeat) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Announcement does not exist.'
            );
        }

        if ($announcementSeat->occupied_seats_counter >= $announcementSeat->maximum_seats_number) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Place limit exceeded.'
            );
        }

        $userExists = $announcementSeat->announcementParticipants()->where('user_id', $user->id)->first();

        if ($userExists) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'You have already signed up.'
            );
        }

        /** @var AnnouncementPayment $announcementPayment */
        $announcementPayment = $announcement->announcementPayments()->first();

        $announcementParticipant = new AnnouncementParticipant;
        $announcementParticipant->user_id = $user->id;
        $announcementParticipant->announcement_seat_id = $announcementSeat->id;
        $announcementParticipant->announcement_payment_id = $announcementPayment->id;
        $announcementParticipant->joining_status_id = 91;
        $announcementParticipant->save();

        $announcementSeat->occupied_seats_counter = $announcementSeat->occupied_seats_counter+1;
        $announcementSeat->save();

        $announcement->participants_counter = $announcement->participants_counter+1;
        $announcement->save();

        JsonResponse::sendSuccess();
    }

    /**
     * #### `DELETE` `/api/v1/announcements/leave`
     * Zapisanie się na wydarzenie
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function leaveAnnouncement(Request $request): void {

        $request->validate([
            'announcement_id' => 'required|integer|exists:announcements,id',
            'announcement_seat_id' => 'required|integer|exists:announcement_seats,id',
            'user_id' => 'required|integer|exists:users,id',
        ]);

        /** @var User $user */
        $user = Auth::user();

        /** @var Announcement $announcement */
        $announcement = Announcement::where('id', $request->announcement_id)->first();

        if (!$announcement) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Announcement does not exist.'
            );
        }

        /** @var Partner $partner */
        $partner = $user->partners()->first();

        $partnerSetting = null;

        if ($partner) {
            /** @var PartnerSetting $partnerSetting */
            $partnerSetting = $partner->partnerSettings()->first();
        }

        /** @var AnnouncementSeat $announcementSeat */
        $announcementSeat = $announcement->announcementSeats()->where('id', $request->announcement_seat_id)->first();

        if (!$announcementSeat) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Announcement does not exist.'
            );
        }

        /** @var AnnouncementParticipant $userExists */
        $userExists = $announcementSeat->announcementParticipants()->where('user_id', $request->user_id)->first();

        if (!$userExists) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Announcement does not exist.'
            );
        }

        if ($userExists->user_id == $user->id || ($partnerSetting && $announcement->announcement_partner_id == $partnerSetting->id)) {

            if ($userExists->user_id == $user->id) {
                $userExists->delete();
            } else {
                $userExists->joining_status_id = 92;
                $userExists->save();
            }

            $announcementSeat->occupied_seats_counter = $announcementSeat->occupied_seats_counter-1;
            $announcementSeat->save();

            $announcement->participants_counter = $announcement->participants_counter-1;
            $announcement->save();

            JsonResponse::sendSuccess();
        } else {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                'Announcement does not exist.'
            );
        }
    }
}
