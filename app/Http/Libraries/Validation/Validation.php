<?php

namespace App\Http\Libraries\Validation;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Models\AccountActionType;
use App\Models\Area;
use App\Models\DefaultType;
use App\Models\DefaultTypeName;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Klasa umożliwiająca przeprowadzanie procesów walidacji danych
 */
class Validation
{
    /**
     * Sprawdzenie czy dana wartość jest unikatowa w bazie danych
     * 
     * @param string $value wartość do sprawdzenia
     * @param mixed $entity encja w której będzie następowało przeszukiwanie pod kątem już występującej wartości
     * @param string $field pole po którym będzie następowało przeszukiwanie
     * 
     * @return bool
     */
    public static function checkUniqueness(string $value, $entity, string $field): bool {
        return empty($entity::where($field, $value)->first()) ? true : false;
    }

    /**
     * Pobranie obiektu nazwy domyślnego typu
     * 
     * @param string $name nazwa domyślnej nazwy typu
     * 
     * @return DefaultTypeName
     */
    public static function getDefaultTypeName(string $name): DefaultTypeName {

        /** @var DefaultTypeName $defaultTypeName */
        $defaultTypeName = DefaultTypeName::where('name', $name)->first();

        if (!$defaultTypeName) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid default type name (' . $name . ').'
            );
        }

        return $defaultTypeName;
    }

    /**
     * Pobranie obiektu domyślnego typu
     * 
     * @param string $name nazwa domyślnego typu
     * @param string $nameDefaultTypeName nazwa domyślnej nazwy typu
     * 
     * @return DefaultType
     */
    public static function getDefaultType(string $name, string $nameDefaultTypeName): DefaultType {

        $defaultTypeName = self::getDefaultTypeName($nameDefaultTypeName);

        /** @var DefaultType $defaultType */
        $defaultType = $defaultTypeName->defaultTypes()->where('name', $name)->first();

        if (!$defaultType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid default type (' . $name . ').'
            );
        }

        return $defaultType;
    }

    /**
     * Pobranie obiektu typu akcji na koncie
     * 
     * @param string $name nazwa typu akcji na koncie
     * 
     * @return AccountActionType
     */
    public static function getAccountActionType(string $name): AccountActionType {

        $defaultType = self::getDefaultType($name, 'ACCOUNT_ACTION_TYPE');

        /** @var AccountActionType $accountActionType */
        $accountActionType = $defaultType->accountActionType()->first();

        return $accountActionType;
    }

    /**
     * Pobranie obiektu typu operacji na koncie
     * 
     * @param string $name nazwa typu operacji na koncie
     * 
     * @return DefaultType
     */
    public static function getAccountOperationType(string $name): DefaultType {
        return self::getDefaultType($name, 'ACCOUNT_OPERATION_TYPE');
    }

    /**
     * Sprawdzenie czy upłynął określony czas
     * 
     * @param string $timeReferencePoint punkt odniesienia względem którego liczony jest czas
     * @param int $timeMarker wartość znacznika czasu przez jak długo jest aktywny
     * @param string $comparator jeden z symboli <, >, == lub ich kombinacja, liczone względem bieżącego czasu
     * @param string $unit jednostka w jakiej wyrażony jest $timeMarker
     * 
     * @return bool
     */
    public static function timeComparison(string $timeReferencePoint, int $timeMarker, string $comparator, string $unit = 'minutes'): bool {

        $now = date('Y-m-d H:i:s');
        $expirationDate = date('Y-m-d H:i:s', strtotime('+' . $timeMarker . ' ' . $unit, strtotime($timeReferencePoint)));

        $comparasion = false;

        switch ($comparator) {

            case '==':
                if ($now == $expirationDate) {
                    $comparasion = true;
                }
                break;

            case '>=':
                if ($now >= $expirationDate) {
                    $comparasion = true;
                }
                break;

            case '>':
                if ($now > $expirationDate) {
                    $comparasion = true;
                }
                break;

            case '<=':
                if ($now <= $expirationDate) {
                    $comparasion = true;
                }
                break;

            case '<':
                if ($now < $expirationDate) {
                    $comparasion = true;
                }
                break;
        }

        return $comparasion;
    }

    /**
     * Sprawdzenie czy wszystkie wymagane zgody zostały zaakceptowane
     * 
     * @param Request $request
     * 
     * @return void
     */
    public static function checkRequiredAgreements(Request $request): void {

        $defaultType = self::getDefaultType('REGISTRATION_FORM', 'AGREEMENT_TYPE');

        /** @var \App\Models\Agreement $requiredAgreements */
        $requiredAgreements = $defaultType->agreements()->where('effective_date', '<=', now())->get();

        /** @var \App\Models\Agreement $lastServiceTerms */
        $lastServiceTerms = null;

        /** @var \App\Models\Agreement $lastPrivacyPolicy */
        $lastPrivacyPolicy = null;

        /** @var \App\Models\Agreement $rA */
        foreach ($requiredAgreements as $rA) {

            if ($rA->contractable_type == null &&
                $rA->contractable_id == null &&
                $rA->signature == 'REGULAMIN_SERWISU' &&
                $rA->agreementType()->first()->name == 'REGISTRATION_FORM')
            {
                if (!$lastServiceTerms || $rA->version > $lastServiceTerms->version) {
                    $lastServiceTerms = $rA;
                }
            }

            if ($rA->contractable_type == null &&
                $rA->contractable_id == null &&
                $rA->signature == 'POLITYKA_PRYWATNOSCI_SERWISU' &&
                $rA->agreementType()->first()->name == 'REGISTRATION_FORM')
            {
                if (!$lastPrivacyPolicy || $rA->version > $lastPrivacyPolicy->version) {
                    $lastPrivacyPolicy = $rA;
                }
            }
        }

        $requiredAgreements = null;

        if ($lastServiceTerms->is_required) {
            $requiredAgreements[] = $lastServiceTerms;
        }

        if ($lastPrivacyPolicy->is_required) {
            $requiredAgreements[] = $lastPrivacyPolicy;
        }

        /** @var \App\Models\Agreement $rA */
        foreach ($requiredAgreements as $rA) {
            if (!in_array($rA->id, $request->accepted_agreements)) {
                if ($rA->signature == 'REGULAMIN_SERWISU') {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'Wymagana akceptacja Regulaminu Serwisu'
                    );
                } else if ($rA->signature == 'POLITYKA_PRYWATNOSCI_SERWISU') {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'Wymagana akceptacja Polityki Prywatności'
                    );
                }
            }
        }
    }

    /**
     *  Stworzenie odpowiednich rekordów w bazie: miasto, gmina, powiat, województwo
     * 
     * @param Request $request
     * 
     * @return Area
     */
    public static function createArea(Request $request): Area {

        /** @var \app\Models\User $user */
        $user = Auth::user();

        $country = null;
        $voivodeship = null;
        $poviat = null;
        $commune = null;
        $city = null;

        if ($request->country_id) {
            $defaultType = self::getDefaultType('COUNTRY', 'AREA_TYPE');
            /** @var Area $country */
            $country = $defaultType->areas()->where('id', $request->country_id)->first();
        }

        if ($request->voivodeship_id) {
            $defaultType = self::getDefaultType('VOIVODESHIP', 'AREA_TYPE');
            /** @var Area $voivodeship */
            $voivodeship = $defaultType->areas()->where('id', $request->voivodeship_id)->first();
        }

        if ($request->poviat_id) {
            $defaultType = self::getDefaultType('POVIAT', 'AREA_TYPE');
            /** @var Area $poviat */
            $poviat = $defaultType->areas()->where('id', $request->poviat_id)->first();
        }

        if ($request->commune_id) {
            $defaultType = self::getDefaultType('COMMUNE', 'AREA_TYPE');
            /** @var Area $commune */
            $commune = $defaultType->areas()->where('id', $request->commune_id)->first();
        }

        if ($request->city_id) {
            $defaultType = self::getDefaultType('CITY', 'AREA_TYPE');
            /** @var Area $city */
            $city = $defaultType->areas()->where('id', $request->city_id)->first();
        }

        if (!$city) {

            if (!$commune) {

                if (!$poviat) {

                    if (!$voivodeship) {

                        if (!$country) {
                            if (!$request->country_name) {
                                throw new ApiException(
                                    BaseErrorCode::FAILED_VALIDATION(),
                                    'Wymagana nazwa kraju'
                                );
                            }

                            $defaultType = self::getDefaultType('COUNTRY', 'AREA_TYPE');

                            /** @var Area $country */
                            $country = $defaultType->areas()->where('name', $request->country_name)->first();
                
                            if (!$country) {
                                $createCountry = $defaultType;
                            }
                        }

                        if (!$request->voivodeship_name) {
                            throw new ApiException(
                                BaseErrorCode::FAILED_VALIDATION(),
                                'Wymagana nazwa województwa'
                            );
                        }

                        $defaultType = self::getDefaultType('VOIVODESHIP', 'AREA_TYPE');
                        $createVoivodeship = $defaultType;
                    }

                    if (!$request->poviat_name) {
                        throw new ApiException(
                            BaseErrorCode::FAILED_VALIDATION(),
                            'Wymagana nazwa powiatu'
                        );
                    }

                    $defaultType = self::getDefaultType('POVIAT', 'AREA_TYPE');
                    $createPoviat = $defaultType;
                }

                if (!$request->commune_name) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'Wymagana nazwa gminy'
                    );
                }

                $defaultType = self::getDefaultType('COMMUNE', 'AREA_TYPE');
                $createCommune = $defaultType;
            }

            if (!$request->city_name) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    'Wymagana nazwa miasta'
                );
            }

            if (isset($createCountry)) {
                $country = new Area;
                $country->name = $request->country_name;
                $country->area_type_id = $createCountry->id;
                $country->creator_id = $user->id;
                $country->editor_id = $user->id;
                $country->save();
            }

            if (isset($createVoivodeship)) {
                $voivodeship = $createVoivodeship->areas()->where('name', $request->voivodeship_name)->where('parent_id', $country->id)->first();

                if (!$voivodeship) {
                    $voivodeship = new Area;
                    $voivodeship->name = $request->voivodeship_name;
                    $voivodeship->area_type_id = $createVoivodeship->id;
                    $voivodeship->parent_id = $country->id;
                    $voivodeship->creator_id = $user->id;
                    $voivodeship->editor_id = $user->id;
                    $voivodeship->save();
                }
            }

            if (isset($createPoviat)) {
                $poviat = $createPoviat->areas()->where('name', $request->poviat_name)->where('parent_id', $voivodeship->id)->first();

                if (!$poviat) {
                    $poviat = new Area;
                    $poviat->name = $request->poviat_name;
                    $poviat->area_type_id = $createPoviat->id;
                    $poviat->parent_id = $voivodeship->id;
                    $poviat->creator_id = $user->id;
                    $poviat->editor_id = $user->id;
                    $poviat->save();
                }
            }

            if (isset($createCommune)) {
                $commune = $createCommune->areas()->where('name', $request->commune_name)->where('parent_id', $poviat->id)->first();

                if (!$commune) {
                    $commune = new Area;
                    $commune->name = $request->commune_name;
                    $commune->area_type_id = $createCommune->id;
                    $commune->parent_id = $poviat->id;
                    $commune->creator_id = $user->id;
                    $commune->editor_id = $user->id;
                    $commune->save();
                }
            }

            $defaultType = self::getDefaultType('CITY', 'AREA_TYPE');
            $city = $defaultType->areas()->where('name', $request->city_name)->where('parent_id', $commune->id)->first();

            if (!$city) {
                $city = new Area;
                $city->name = $request->city_name;
                $city->area_type_id = $defaultType->id;
                $city->parent_id = $commune->id;
                $city->creator_id = $user->id;
                $city->editor_id = $user->id;
                $city->save();
            }
        }

        return $city;
    }
}
