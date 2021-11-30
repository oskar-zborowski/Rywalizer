<?php

namespace App\Http\Middleware\Authenticate;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\Responses\JsonResponse;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

/**
 * Klasa wywoływana przed autoryzacją
 */
class Authenticate extends Middleware
{
    /**
     * @param Illuminate\Http\Request $request
     */
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

        $exceptionalURLs['login'] = env('APP_URL') . '/api/login';
        $exceptionalURLs['register'] = env('APP_URL') . '/api/register';
        $exceptionalURLs['forgotPassword'] = env('APP_URL') . '/api/forgot-password';
        $exceptionalURLs['resetPassword'] = env('APP_URL') . '/api/reset-password';

        $externalAuthenticationURL = env('APP_URL') . '/api/auth';
        $logoutURL = env('APP_URL') . '/api/logout';

        if ($jwt = $request->cookie('JWT')) {

            $request->headers->set('Authorization', 'Bearer ' . $jwt);
            $authenticated = true;

            try {
                $this->authenticate($request, $guards);
            } catch (AuthenticationException $e) {

                JsonResponse::deleteCookie('JWT');

                $personalAccessToken = JsonResponse::isRefreshTokenValid($request);

                if ($personalAccessToken) {

                    if (in_array($request->url(), $exceptionalURLs) ||
                        strpos($request->url(), $externalAuthenticationURL) !== false)
                    {
                        throw new ApiException(AuthErrorCode::REFRESH_TOKEN_IS_STILL_ACTIVE());
                    }

                    if ($request->url() == $logoutURL) {
                        JsonResponse::deleteCookie('REFRESH-TOKEN');
                    } else {
                        JsonResponse::refreshToken($personalAccessToken);
                    }

                } else {

                    if ($request->url() == $logoutURL) {
                        JsonResponse::sendSuccess();
                    }

                    if (!in_array($request->url(), $exceptionalURLs) &&
                        strpos($request->url(), $externalAuthenticationURL) === false)
                    {
                        throw new ApiException(AuthErrorCode::UNAUTHORIZED());
                    }

                    $authenticated = false;
                }
            }

            if ($authenticated) {

                JsonResponse::checkUserAccess($request);

                if (in_array($request->url(), $exceptionalURLs) ||
                    strpos($request->url(), $externalAuthenticationURL) !== false)
                {
                    throw new ApiException(AuthErrorCode::ALREADY_LOGGED_IN());
                }
            }

        } else {

            $personalAccessToken = JsonResponse::isRefreshTokenValid($request);

            if ($personalAccessToken) {

                if (in_array($request->url(), $exceptionalURLs) ||
                    strpos($request->url(), $externalAuthenticationURL) !== false)
                {
                    throw new ApiException(AuthErrorCode::REFRESH_TOKEN_IS_STILL_ACTIVE());
                }

                if ($request->url() == $logoutURL) {
                    JsonResponse::deleteCookie('REFRESH-TOKEN');
                } else {
                    JsonResponse::refreshToken($personalAccessToken);
                    JsonResponse::checkUserAccess($request);
                }

            } else {

                if ($request->url() == $logoutURL) {
                    JsonResponse::sendSuccess();
                }

                if (!in_array($request->url(), $exceptionalURLs) &&
                    strpos($request->url(), $externalAuthenticationURL) === false)
                {
                    throw new ApiException(AuthErrorCode::UNAUTHORIZED());
                }
            }
        }

        return $next($request);
    }
}
