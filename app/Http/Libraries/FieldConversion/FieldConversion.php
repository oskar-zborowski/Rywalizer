<?php

namespace App\Http\Libraries\FieldConversion;

use Illuminate\Support\Str;

/**
 * Klasa umożliwiająca konwersję pomiędzy formatami CamelCase, a snake_case
 */
class FieldConversion
{
    /**
     * Konwersja nazw pól na formę camelCase
     * 
     * @param array $data dane wychodzące
     * @param int $from rząd wielkości od którego pola mają być przetwarzane dane
     * @param int $to rząd wielkości do którego pola mają być przetwarzane dane
     * 
     * @return array
     */
    public static function convertToCamelCase(array $data, int $from = 0, int $to = null): array {
        return self::convertByDefault('camel', $data, $from, $to, 0);
    }

    /**
     * Konwersja nazw pól na formę snake_case
     * 
     * @param $data dane przychodzące
     * @param int $from rząd wielkości od którego pola mają być przetwarzane dane
     * @param int $to rząd wielkości do którego pola mają być przetwarzane dane
     * 
     * @return array|string|null
     */
    public static function convertToSnakeCase($data, int $from = 0, int $to = null) {
        return self::convertByDefault('snake', $data, $from, $to, 0);
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

    /**
     * Uniwersalna konwersja nazw pól
     * 
     * @param string $conversionType informacja o typie konwersji (camel, snake)
     * @param $data dane podlegające konwersji
     * @param int $from rząd wielkości od którego pola mają być przetwarzane dane
     * @param int $to rząd wielkości do którego pola mają być przetwarzane dane
     * @param int $current bieżący rząd wielkości
     * 
     * @return array|string|null
     */
    private static function convertByDefault(string $conversionType, $data, int $from = 0, int $to = null, int $current) {

        if (is_array($data) || $current > 0) {

            $fieldNames = null;

            if ($data && (isset($to) && $from <= $to || !isset($to))) {
    
                if ($current == 0) {
                    $data = json_encode($data);
                    $data = json_decode($data, true);
                }
    
                foreach ($data as $key => $value) {
                    if (is_array($value)) {
                        if (isset($to)) {
                            if ($current >= $from && $current <= $to) {
                                $fieldNames[Str::$conversionType($key)] = self::convertByDefault($conversionType, $value, $from, $to, $current+1);
                            } else if ($current < $from) {
                                $deep = self::convertByDefault($conversionType, $value, $from, $to, $current+1);
    
                                foreach ($deep as $k => $v) {
                                    $fieldNames[Str::$conversionType($k)] = $v;
                                }
                            }
                        } else {
                            if ($current < $from) {
                                $deep = self::convertByDefault($conversionType, $value, $from, $to, $current+1);
    
                                foreach ($deep as $k => $v) {
                                    $fieldNames[Str::$conversionType($k)] = $v;
                                }
                            } else {
                                $fieldNames[Str::$conversionType($key)] = self::convertByDefault($conversionType, $value, $from, $to, $current+1);
                            }
                        }
                    } else {
                        if ($current >= $from) {
                            if (isset($to) && $current <= $to || !isset($to)) {
                                $fieldNames[Str::$conversionType($key)] = $value;
                            } else {
                                $fieldNames = null;
                            }
                        } else {
                            $fieldNames[] = chr(27);
                        }
                    }
                }
            }

            if ($current == 0 && $fieldNames != null) {

                $fN = null;

                foreach ($fieldNames as $key => $value) {
                    if ($value !== chr(27)) {
                        $fN[$key] = $value;
                    }
                }

                $fieldNames = $fN;
            }

        } else {
            $fieldNames = $data;
        }

        return $fieldNames;
    }
}
