<?php

namespace App\Http\Responses;

use App\Http\ErrorCodes\ErrorCode;
use App\Http\Libraries\FieldsConversion\FieldConversion;
use Symfony\Component\HttpFoundation\Response;

/**
 * Klasa przeznaczona do wysyłania odpowiedzi zwrotnych do klienta
 */
class JsonResponse
{
    /**
     * Wysłanie pomyślnej odpowiedzi
     * 
     * @param mixed $data podstawowe informacje zwrotne
     * @param mixed $metadata dodatkowe informacje
     * 
     * @return void
     */
    public static function sendSuccess(mixed $data = null, mixed $metadata = null): void {

        header('Content-Type: application/json');
        http_response_code(Response::HTTP_OK);

        echo json_encode([
            'data' => FieldConversion::convertToCamelCase($data),
            'metadata' => FieldConversion::convertToCamelCase($metadata)
        ]);

        die;
    }

    /**
     * Wysłanie odpowiedzi z błędem
     * 
     * @param App\Http\ErrorCodes\ErrorCode $errorCode obiekt kodu błędu
     * @param mixed $data podstawowe informacje zwrotne
     * @param mixed $metadata dodatkowe informacje
     * 
     * @return void
     */
    public static function sendError(ErrorCode $errorCode, mixed $data = null, mixed $metadata = null): void {

        header('Content-Type: application/json');
        http_response_code($errorCode->getHttpStatus());

        $dataToSend = [];

        if (env('APP_DEBUG')) {
            $dataToSend['errorMessage'] = $errorCode->getMessage();
        }

        $dataToSend += [
            'errorCode' => $errorCode->getCode(),
            'data' => $data,
            'metadata' => $metadata
        ];

        echo json_encode($dataToSend);

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
                $expires = time()+env('JWT_LIFETIME')*60;
                break;

            case 'REFRESH-TOKEN':
                $expires = time()+env('REFRESH_TOKEN_LIFETIME')*60;
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
        setcookie($name, null, -1, '/', env('APP_DOMAIN'), true, true);
    }
}
