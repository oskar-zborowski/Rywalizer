<?php

namespace App\Http\Libraries\Validation;

use App\Models\User;

/**
 * Klasa umożliwiająca przeprowadzanie procesów walidacji danych
 */
class Validation
{
    /**
     * Sprawdzenie czy dana wartość jest unikatowa dla modelu User
     * 
     * @param string $field pole względem którego następuje przeszukiwanie
     * @param string $value wartość do sprawdzenia
     * 
     * @return bool
     */
    public static function checkUserUniqueness(string $field, string $value): bool {

        /** @var User $userExist */
        $userExist = User::where($field, $value)->first();

        return $userExist ? false : true;
    }

    /**
     * Sprawdzenie czy upłynął określony czas
     * 
     * @param string $timeReferencePoint punkt odniesienia, względem którego liczony jest czas
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
     * Metoda pozwala wybrać ze stringa ciąg znaków pomiędzy innymi stringami
     * 
     * @param string $string cały ciąg znaków
     * @param string $start rozpoczynający ciąg znaków
     * @param string $end kończący ciąg znaków
     * 
     * @return string
     */
    public static function getStringBetweenOthers(string $string, string $start = '', string $end = ''): string {

        $startCharCount = strpos($string, $start);
        $endCharCount = strpos($string, $end);

        if ($startCharCount !== false) {
            $startCharCount += strlen($start);
            $string = substr($string, $startCharCount, strlen($string));
            $endCharCount = strpos($string, $end);
        }

        if ($endCharCount == 0) {
            $endCharCount = strlen($string);
        }

        $result = substr($string, 0, $endCharCount);

        return $result;
    }
}
