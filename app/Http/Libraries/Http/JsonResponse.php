<?php

namespace App\Http\Libraries\Http;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Klasa umożliwiająca przeprowadzanie operacji na zwracanych odpowiedziach
 */
class JsonResponse
{
    /**
     * Wysyłanie pomyślnej odpowiedzi
     * 
     * @param array $data tablica z informacjami kierowanymi do wysłania odpowiedzi
     * @param array $metadata tablica z dodatkowymi informacjami kierowanymi do wysłania odpowiedzi
     * 
     * @return void
     */
    public static function sendSuccess(array $data = null, array $metadata = null): void {

        header('Content-Type: application/json');
        http_response_code(Response::HTTP_OK);

        echo json_encode([
            'data' => JsonResponse::convertToCamelCase($data),
            'metadata' => JsonResponse::convertToCamelCase($metadata)
        ]);

        die;
    }

    /**
     * Wysyłanie odpowiedzi z błędem
     * 
     * @param App\Http\Responses\AuthResponse $errorCode kod błędu
     * @param Symfony\Component\HttpFoundation\Response $status kod odpowiedzi HTTP
     * @param array $data tablica z informacjami kierowanymi do wysłania odpowiedzi
     * @param array $metadata tablica z dodatkowymi informacjami kierowanymi do wysłania odpowiedzi
     * 
     * @return void
     */
    public static function sendError(string $errorCode, int $status, array $data = null, array $metadata = null): void {

        header('Content-Type: application/json');
        http_response_code($status);

        echo json_encode([
            'errorCode' => $errorCode,
            'data' => JsonResponse::convertToCamelCase($data),
            'metadata' => JsonResponse::convertToCamelCase($metadata)
        ]);

        die;
    }

    /**
     * Ustawianie ciasteczka
     * 
     * @param string $value zawartość ciasteczka
     * @param string $name nazwa ciasteczka
     * 
     * @return void
     */
    public static function setCookie(string $value, string $name = 'JWT'): void {

        if ($name == 'JWT') {
            $expires = time()+env('JWT_COOKIE_LIFETIME')*60;
        } else if ($name == 'REFRESH-TOKEN') {
            $expires = time()+env('REFRESH_TOKEN_COOKIE_LIFETIME')*60;
        } else {
            $expires = time()+env('DEFAULT_COOKIE_LIFETIME')*60;
        }

        setcookie($name, $value, $expires); // TODO Zastanowić się nad $secure (raczej powinno być na true) oraz $httponly
    }

    /**
     * Usuwanie ciasteczka
     * 
     * @param string $name nazwa ciasteczka
     * 
     * @return void
     */
    public static function deleteCookie(string $name = 'JWT'): void {
        setcookie($name, null, -1); // TODO Zastanowić się nad $secure (raczej powinno być na true) oraz $httponly
    }

    /**
     * Konwersja nazw pól na formę camelCase
     * 
     * @param array $data tablica z informacjami kierowanymi do wysłania odpowiedzi
     * @param int $from rząd wielkości od którego pola mają być przetwarzane dane
     * @param int $to rząd wielkości do którego pola mają być przetwarzane dane
     * 
     * @return array
     */
    public static function convertToCamelCase(array $data = null, int $from = 0, int $to = null, int $current = 0) {

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
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);
                        } else if ($current < $from) {
                            $deep = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);

                            foreach ($deep as $k => $v) {
                                $fieldNames[Str::camel($k)] = $v;
                            }
                        }
                    } else {
                        if ($current < $from) {
                            $deep = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);

                            foreach ($deep as $k => $v) {
                                $fieldNames[Str::camel($k)] = $v;
                            }
                        } else {
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);
                        }
                    }
                } else {
                    if ($current >= $from) {
                        if (isset($to) && $current <= $to || !isset($to)) {
                            $fieldNames[Str::camel($key)] = $value;
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
                if ($value != chr(27)) {
                    $fN[$key] = $value;
                }
            }

            $fieldNames = $fN;
        }

        return $fieldNames;
    }
}