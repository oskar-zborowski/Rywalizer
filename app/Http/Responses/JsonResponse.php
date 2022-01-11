<?php

namespace App\Http\Responses;

use App\Http\ErrorCodes\ErrorCode;
use App\Http\Libraries\FieldConversion\FieldConversion;
use Symfony\Component\HttpFoundation\Response;

/**
 * Klasa obsługująca wysyłanie odpowiedzi do klienta
 */
class JsonResponse
{
    /**
     * Wysłanie pomyślnej odpowiedzi
     * 
     * @param $data podstawowe informacje zwrotne
     * @param $metadata dodatkowe informacje
     * 
     * @return void
     */
    public static function sendSuccess($data = null, $metadata = null): void {

        header('Content-Type: application/json');
        http_response_code(Response::HTTP_OK);

        $response = FieldConversion::convertToCamelCase([
            'data' => $data,
            'metadata' => $metadata
        ]);

        echo json_encode($response);
        die;
    }

    /**
     * Wysłanie odpowiedzi z błędem
     * 
     * @param ErrorCode $errorCode obiekt kodu błędu
     * @param $data podstawowe informacje zwrotne
     * @param $metadata dodatkowe informacje
     * 
     * @return void
     */
    public static function sendError(ErrorCode $errorCode, $data = null, $metadata = null): void {

        header('Content-Type: application/json');
        http_response_code($errorCode->getHttpStatus());

        $response = [];

        if (env('APP_DEBUG')) {
            $response['error_message'] = $errorCode->getMessage();
        }

        $response += [
            'error_code' => $errorCode->getCode(),
            'data' => $data,
            'metadata' => $metadata
        ];

        $response = FieldConversion::convertToCamelCase($response);

        echo json_encode($response);
        die;
    }

    /**
     * Ustawienie ciasteczka
     * 
     * @param string $value zawartość ciasteczka
     * @param string $name nazwa ciasteczka
     * 
     * @return void
     */
    public static function setCookie(string $value, string $name): void {

        switch ($name) {

            case 'JWT':
                $name = env('JWT_COOKIE_NAME');
                $expires = time()+env('JWT_LIFETIME')*60;
                break;

            case 'REFRESH-TOKEN':
                $name = env('REFRESH_TOKEN_COOKIE_NAME');
                $expires = time()+env('REFRESH_TOKEN_LIFETIME')*60;
                break;

            case 'UUID':
                $name = env('UUID_COOKIE_NAME');
                $expires = time()+env('UUID_LIFETIME')*60;
                break;

            default:
                $expires = time()+env('DEFAULT_COOKIE_LIFETIME')*60;
                break;
        }

        setcookie($name, $value, $expires, '/', env('APP_DOMAIN'), true, true);
    }

    /**
     * Usuwanie ciasteczka
     * 
     * @param string $name nazwa ciasteczka
     * 
     * @return void
     */
    public static function deleteCookie(string $name): void {

        if ($name == 'JWT') {
            $name = env('JWT_COOKIE_NAME');
        } else if ($name == 'REFRESH-TOKEN') {
            $name = env('REFRESH_TOKEN_COOKIE_NAME');
        }

        setcookie($name, null, -1, '/', env('APP_DOMAIN'), true, true);
    }
}
