<?php

namespace App\Http\Responses;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\ErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\FieldConversion\FieldConversion;
use App\Http\Libraries\Validation\Validation;
use App\Models\PersonalAccessToken;
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
     * @param App\Http\ErrorCodes\ErrorCode $errorCode obiekt kodu błędu
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
     * Stworzenie ciasteczek JWT oraz REFRESH-TOKEN
     * 
     * @return void
     */
    public static function prepareCookies(): void {

        /** @var User $user */
        $user = Auth::user();

        $encrypter = new Encrypter;
        $refreshToken = $encrypter->generateToken(64);
        $encryptedRefreshToken = $encrypter->encrypt($refreshToken);

        $jwtEncryptedName = $encrypter->encrypt('JWT', 3);
        $jwt = $user->createToken($jwtEncryptedName);
        $jwtToken = $jwt->plainTextToken;
        $jwtId = $jwt->accessToken->getKey();

        $user->personalAccessToken()->where('id', $jwtId)->update(['refresh_token' => $encryptedRefreshToken]);
        $user->update(['last_logged_in' => now()]);

        self::setCookie($jwtToken, 'JWT');
        self::setCookie($refreshToken, 'REFRESH-TOKEN');
    }

    /**
     * Odświeżenie tokenów uwierzytelniających
     * 
     * @param App\Models\PersonalAccessToken $personalAccessToken obiekt tokenu uwierzytelniającego
     * 
     * @return void
     */
    public static function refreshToken(PersonalAccessToken $personalAccessToken): void {

        Auth::loginUsingId($personalAccessToken->tokenable_id);

        $personalAccessToken->delete();

        self::prepareCookies();
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

    /**
     * Sprawdzenie czy REFRESH-TOKEN jest ważny
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return App\Models\PersonalAccessToken|null
     */
    public static function isRefreshTokenValid(Request $request): ?PersonalAccessToken {

        $personalAccessToken = null;

        if ($refreshToken = $request->cookie('REFRESH-TOKEN')) {

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
     * Sprawdzenie czy użytkownik może korzystać z serwisu
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public static function checkUserAccess(Request $request = null): void {

        /** @var User $user */
        $user = Auth::user();

        if (($user->account_blocked_at || $user->account_deleted_at)) {

            self::deleteCookie('JWT');

            if ($request && $request->cookie('REFRESH-TOKEN')) {
                self::deleteCookie('REFRESH-TOKEN');
            }

            if ($user->account_blocked_at) {
                $user->tokens()->delete();
                throw new ApiException(AuthErrorCode::ACOUNT_BLOCKED());
            }

            if ($user->account_deleted_at) {
                $user->tokens()->delete();
                throw new ApiException(AuthErrorCode::ACOUNT_DELETED());
            }
        }
    }
}
