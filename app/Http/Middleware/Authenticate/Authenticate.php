<?php

namespace App\Http\Middleware\Authenticate;

use App\Http\Responses\JsonResponse;
use App\Http\ErrorCodes\AuthErrorCode;
use Closure;
use App\Exceptions\ApiException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Klasa wywoływana przed autoryzacją
 */
class Authenticate extends Middleware
{
    protected function redirectTo($request) {
        // if (!$request->expectsJson()) {
        //     return route('login');
        // }
    }

    /**
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     */
    public function handle($request, Closure $next, ...$guards) {

        /** @var Request $request */

        $loginURL = env('APP_URL') . '/api/login';
        $registerURL = env('APP_URL') . '/api/register';
        $forgotPasswordURL = env('APP_URL') . '/api/forgot-password';
        $resetPasswordURL = env('APP_URL') . '/api/reset-password';
        $externalAuthenticationURL = env('APP_URL') . '/api/auth';
        $logoutURL = env('APP_URL') . '/api/logout';

        if ($jwt = $request->cookie('JWT')) {

            $request->headers->set('Authorization', 'Bearer ' . $jwt);

            $authenticated = true;

            try {
                $this->authenticate($request, $guards);
            } catch (AuthenticationException $e) {

                JsonResponse::deleteCookie('JWT');

                $data = JsonResponse::isRefreshTokenValid($request);

                if ($data['userId']) {

                    if ($request->url() == $loginURL ||
                        $request->url() == $registerURL ||
                        $request->url() == $forgotPasswordURL ||
                        $request->url() == $resetPasswordURL ||
                        strpos($request->url(), $externalAuthenticationURL) !== false)
                    {
                        throw new ApiException(AuthErrorCode::REFRESH_TOKEN_IS_STILL_ACTIVE());
                    }

                    JsonResponse::refreshToken($data['userId'], $data['personalAccessTokenId']);

                } else {

                    if ($request->url() != $loginURL &&
                        $request->url() != $registerURL &&
                        $request->url() != $forgotPasswordURL &&
                        $request->url() != $resetPasswordURL &&
                        strpos($request->url(), $externalAuthenticationURL) === false)
                    {
                        if ($request->url() == $logoutURL) {
                            JsonResponse::sendSuccess();
                        }
                        
                        throw new ApiException(AuthErrorCode::UNAUTHORIZED());
                    }

                    $authenticated = false;
                }
            }

            if ($authenticated) {

                if ($request->url() == $loginURL ||
                    $request->url() == $registerURL ||
                    $request->url() == $forgotPasswordURL ||
                    $request->url() == $resetPasswordURL ||
                    strpos($request->url(), $externalAuthenticationURL) !== false)
                {
                    throw new ApiException(AuthErrorCode::ALREADY_LOGGED_IN());
                }
            }

        } else {

            $data = JsonResponse::isRefreshTokenValid($request);

            if ($data['userId']) {

                if ($request->url() == $loginURL ||
                    $request->url() == $registerURL ||
                    $request->url() == $forgotPasswordURL ||
                    $request->url() == $resetPasswordURL ||
                    strpos($request->url(), $externalAuthenticationURL) !== false)
                {
                    throw new ApiException(AuthErrorCode::REFRESH_TOKEN_IS_STILL_ACTIVE());
                }

                JsonResponse::refreshToken($data['userId'], $data['personalAccessTokenId']);

            } else {

                if ($request->url() != $loginURL &&
                    $request->url() != $registerURL &&
                    $request->url() != $forgotPasswordURL &&
                    $request->url() != $resetPasswordURL &&
                    strpos($request->url(), $externalAuthenticationURL) === false)
                {
                    if ($request->url() == $logoutURL) {
                        JsonResponse::sendSuccess();
                    }

                    throw new ApiException(AuthErrorCode::UNAUTHORIZED());
                }
            }
        }

        return $next($request);
    }
}
