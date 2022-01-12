<?php

namespace App\Http\Libraries\Validation;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Models\AccountActionType;
use App\Models\DefaultType;
use App\Models\DefaultTypeName;

/**
 * Klasa umożliwiająca przeprowadzanie procesów walidacji danych
 */
class Validation
{
    /**
     * Sprawdzenie czy dana wartość jest unikatowa w bazie danych
     * 
     * @param string $value wartość do sprawdzenia
     * @param $entity encja w której będzie następowało przeszukiwanie pod kątem już występującej wartości
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
     * @param string $name nazwa domyślnego typu
     * 
     * @return DefaultTypeName|null
     */
    public static function getDefaultTypeName(string $name): ?DefaultTypeName {

        /** @var DefaultTypeName $defaultTypeName */
        $defaultTypeName = DefaultTypeName::where('name', $name)->first();

        return $defaultTypeName ?? $defaultTypeName;
    }

    /**
     * Pobranie obiektu typu akcji na koncie
     * 
     * @param string $name nazwa typu akcji na koncie
     * 
     * @return AccountActionType|null
     */
    public static function getAccountActionType(string $name): ?AccountActionType {

        $defaultTypeName = self::getDefaultTypeName('ACCOUNT_ACTION_TYPE');

        if (!$defaultTypeName) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid default type name (ACCOUNT_ACTION_TYPE).'
            );
        }

        /** @var DefaultType $defaultType */
        $defaultType = $defaultTypeName->defaultTypes()->where('name', $name)->first();

        if (!$defaultType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid default type (' . $name . ').'
            );
        }

        /** @var AccountActionType $accountActionType */
        $accountActionType = $defaultType->accountActionType()->first();

        return $accountActionType ?? $accountActionType;
    }

    /**
     * Pobranie obiektu typu operacji na koncie
     * 
     * @param string $name nazwa typu operacji na koncie
     * 
     * @return DefaultType|null
     */
    public static function getAccountOperationType(string $name): ?DefaultType {

        $defaultTypeName = self::getDefaultTypeName('ACCOUNT_OPERATION_TYPE');

        if (!$defaultTypeName) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid default type name (ACCOUNT_OPERATION_TYPE).'
            );
        }

        /** @var DefaultType $accountOperationType */
        $accountOperationType = $defaultTypeName->defaultTypes()->where('name', $name)->first();

        return $accountOperationType ?? $accountOperationType;
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
}
