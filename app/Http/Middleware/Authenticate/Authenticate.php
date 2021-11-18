<?php

namespace App\Http\Middleware\Authenticate;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Http\JsonResponse;
use App\Http\ErrorCode\AuthErrorCode;
use Closure;
use App\Exceptions\ApiException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Klasa wywoływana przed autoryzacją
 */
class Authenticate extends Middleware
{
    protected function redirectTo($request) {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     */
    public function handle($request, Closure $next, ...$guards) {

        $loginURL = env('APP_URL') . '/api/login';
        $registerURL = env('APP_URL') . '/api/register';
        $forgotPasswordURL = env('APP_URL') . '/api/forgot-password';
        $resetPasswordURL = env('APP_URL') . '/api/reset-password';
        $refreshTokenURL = env('APP_URL') . '/api/refresh-token';
        $externalAuthenticationURL = env('APP_URL') . '/api/auth';

        if ($jwt = $request->cookie('JWT')) {

            $request->headers->set('Authorization', 'Bearer ' . $jwt);

            try {
                $this->authenticate($request, $guards);
            } catch (AuthenticationException $e) {

                JsonResponse::deleteCookie('JWT');

                if ($request->url() != $loginURL &&
                    $request->url() != $registerURL &&
                    $request->url() != $forgotPasswordURL &&
                    $request->url() != $resetPasswordURL &&
                    $request->url() != $refreshTokenURL &&
                    strpos($request->url(), $externalAuthenticationURL) === false)
                {
                    throw new ApiException(AuthErrorCode::UNAUTHORIZED());
                }
            }

            if ($request->url() == $loginURL ||
                $request->url() == $registerURL ||
                $request->url() == $forgotPasswordURL ||
                $request->url() == $resetPasswordURL ||
                $request->url() == $refreshTokenURL ||
                strpos($request->url(), $externalAuthenticationURL) !== false)
            {
                throw new ApiException(AuthErrorCode::ALREADY_LOGGED_IN());
            }

            $accountBlockedAt = $request->user()->account_blocked_at;

            if ($accountBlockedAt) {

                $request->user()->tokens()->delete();
                
                JsonResponse::deleteCookie('JWT');
                JsonResponse::deleteCookie('REFRESH-TOKEN');
                
                throw new ApiException(AuthErrorCode::ACOUNT_BLOCKED());
            }
        } else {

            if ($request->url() != $loginURL &&
                $request->url() != $registerURL &&
                $request->url() != $forgotPasswordURL &&
                $request->url() != $resetPasswordURL &&
                $request->url() != $refreshTokenURL &&
                strpos($request->url(), $externalAuthenticationURL) === false)
            {
                throw new ApiException(AuthErrorCode::UNAUTHORIZED());
            }
        }

        if ($plainRefreshToken = $request->cookie('REFRESH-TOKEN')) {

            if ($request->url() == $loginURL ||
                $request->url() == $registerURL ||
                $request->url() == $forgotPasswordURL ||
                $request->url() == $resetPasswordURL ||
                strpos($request->url(), $externalAuthenticationURL) !== false)
            {
                $encrypter = new Encrypter;
                $refreshToken = $encrypter->encryptToken($plainRefreshToken);
                $personalAccessToken = DB::table('personal_access_tokens')->where('refresh_token', $refreshToken)->first();
    
                if ($personalAccessToken) {
                    throw new ApiException(AuthErrorCode::REFRESH_TOKEN_IS_STILL_ACTIVE());
                } else {
                    JsonResponse::deleteCookie('REFRESH-TOKEN');
                }
            }
        } else if ($request->url() == $refreshTokenURL) {
            throw new ApiException(AuthErrorCode::UNAUTHORIZED());
        }

        return $next($request);
    }
}
