<?php

namespace App\Http\Responses;

use App\Http\ErrorCodes\ErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\FieldConversion\FieldConversion;
use App\Http\Libraries\Validation\Validation;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Klasa przeznaczona do wysyłania odpowiedzi zwrotnych do klienta
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

        if ($data === null && $metadata === null) {
            $response = 'Success';
        } else {

            if ($data !== null) {
                $response['data'] = $data;
            }

            if ($metadata !== null) {
                $response['metadata'] = $metadata;
            }

            $response = FieldConversion::convertToCamelCase($response);
        }

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

        $response['error_code'] = $errorCode->getCode();

        if (env('APP_DEBUG')) {
            $response['error_message'] = $errorCode->getMessage();
        }

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($metadata !== null) {
            $response['metadata'] = $metadata;
        }

        $response = FieldConversion::convertToCamelCase($response);

        echo json_encode($response);
        die;
    }

    /**
     * Odświeżenie tokenów uwierzytelniających
     * 
     * @param PersonalAccessToken $personalAccessToken obiekt tokenu uwierzytelniającego
     * @param Request $request
     * 
     * @return void
     */
    public static function refreshToken(PersonalAccessToken $personalAccessToken, Request $request): void {

        Auth::loginUsingId($personalAccessToken->tokenable_id);

        $personalAccessToken->delete();

        /** @var User $user */
        $user = Auth::user();
        $user->checkDevice($request->device_id, 'REFRESH_TOKEN');
        $user->createTokens();
    }

    /**
     * Sprawdzenie czy REFRESH-TOKEN jest ważny
     * 
     * @param Request $request
     * 
     * @return PersonalAccessToken|null
     */
    public static function isRefreshTokenValid(Request $request): ?PersonalAccessToken {

        $personalAccessToken = null;

        if ($refreshToken = $request->cookie(env('REFRESH_TOKEN_COOKIE_NAME'))) {

            $encrypter = new Encrypter;
            $encryptedRefreshToken = $encrypter->encrypt($refreshToken);

            /** @var PersonalAccessToken $personalAccessToken */
            $personalAccessToken = PersonalAccessToken::where('refresh_token', $encryptedRefreshToken)->first();
    
            if ($personalAccessToken) {
                if (Validation::timeComparison($personalAccessToken->created_at, env('REFRESH_TOKEN_LIFETIME'), '>')) {
                    $personalAccessToken->delete();
                    $personalAccessToken = null;
                    self::deleteCookie('REFRESH-TOKEN');
                }
            } else {
                self::deleteCookie('REFRESH-TOKEN');
            }
        }

        return $personalAccessToken;
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
