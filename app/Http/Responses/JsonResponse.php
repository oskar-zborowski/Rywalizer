<?php

namespace App\Http\Responses;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\ErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\FieldsConversion\FieldConversion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
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
     * Stworzenie ciasteczek JWT oraz REFRESH-TOKEN
     * 
     * @return void
     */
    public static function prepareCookies(): void {

        /** @var User $user */
        $user = Auth::user();

        $encrypter = new Encrypter;

        $plainRefreshToken = $encrypter->generatePlainToken(64);
        $refreshToken = $encrypter->encryptToken($plainRefreshToken);

        $jwtEncryptedName = $encrypter->encrypt('JWT', 3);
        $jwt = $user->createToken($jwtEncryptedName);
        $plainJWT = $jwt->plainTextToken;
        $jwtId = $jwt->accessToken->getKey();

        DB::table('personal_access_tokens')
            ->where('id', $jwtId)
            ->update(['refresh_token' => $refreshToken]);

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'last_logged_in' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        JsonResponse::setCookie($plainJWT, 'JWT');
        JsonResponse::setCookie($plainRefreshToken, 'REFRESH-TOKEN');
    }

    /**
     * Odświeżenie tokenu autoryzacyjnego
     * 
     * @param int $userId id autoryzowanego użytkownika
     * @param int $personalAccessTokenId id rekordu w bazie z tokenem uwierzytelniającym
     * 
     * @return void
     */
    public static function refreshToken(int $userId, int $personalAccessTokenId): void {

        if ($userId) {

            Auth::loginUsingId($userId);

            /** @var User $user */
            $user = Auth::user();

            $accountDeletedAt = $user->account_deleted_at;
            $accountBlockedAt = $user->account_blocked_at;

            if ($accountBlockedAt) {
                $user->tokens()->delete();
                JsonResponse::deleteCookie('REFRESH-TOKEN');
                throw new ApiException(AuthErrorCode::ACOUNT_BLOCKED());
            }

            if ($accountDeletedAt) {
                $user->tokens()->delete();    
                JsonResponse::deleteCookie('REFRESH-TOKEN');
                throw new ApiException(AuthErrorCode::ACOUNT_DELETED());
            }

            DB::table('personal_access_tokens')
                ->where('id', $personalAccessTokenId)
                ->delete();

            self::prepareCookies();

        } else {
            throw new ApiException(AuthErrorCode::UNIDENTIFIED_USER());
        }
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
     * @param bool $withExceptions parametr określający czy w przypadku napotkania wyjątku ma być on zwrócony
     * 
     * @return array
     */
    public static function isRefreshTokenValid(Request $request, bool $withExceptions = false): array {

        $data['userId'] = 0;

        if ($plainRefreshToken = $request->cookie('REFRESH-TOKEN')) {

            $encrypter = new Encrypter;

            $refreshToken = $encrypter->encryptToken($plainRefreshToken);

            $personalAccessToken = DB::table('personal_access_tokens')
                ->where('refresh_token', $refreshToken)
                ->first();
    
            if (!$personalAccessToken) {

                JsonResponse::deleteCookie('REFRESH-TOKEN');

                if ($withExceptions) {
                    throw new ApiException(AuthErrorCode::INVALID_REFRESH_TOKEN());
                }

            } else {

                $personalAccessTokenId = $personalAccessToken->id;
        
                $now = date('Y-m-d H:i:s');
                $expirationDate = date('Y-m-d H:i:s', strtotime('+' . env('REFRESH_TOKEN_LIFETIME') . ' minutes', strtotime($personalAccessToken->created_at)));

                if ($now <= $expirationDate) {
                    $data['userId'] = $personalAccessToken->tokenable_id;
                    $data['personalAccessTokenId'] = $personalAccessTokenId;
                } else {

                    JsonResponse::deleteCookie('REFRESH-TOKEN');
    
                    DB::table('personal_access_tokens')
                        ->where('id', $personalAccessTokenId)
                        ->delete();

                    if ($withExceptions) {
                        throw new ApiException(AuthErrorCode::REFRESH_TOKEN_HAS_EXPIRED());
                    }
                }
            }

        } else {
            if ($withExceptions) {
                throw new ApiException(AuthErrorCode::REFRESH_TOKEN_HAS_EXPIRED());
            }
        }

        return $data;
    }
}
