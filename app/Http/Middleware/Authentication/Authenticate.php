<?php

namespace App\Http\Middleware\Authentication;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\Responses\JsonResponse;
use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

        $currentRootName = Route::currentRouteName();

        $exceptionalRouteNames = [
            'auth-login',
            'auth-register',
            'auth-forgotPassword',
            'auth-resetPassword',
            'auth-redirectToProvider',
            'auth-handleProviderCallback'
        ];

        $independentRouteNames = [
            'auth-getProviderTypes'
        ];

        $logout = 'auth-logoutMe';

        if ($jwt = $request->cookie(env('JWT_COOKIE_NAME'))) {

            $request->headers->set('Authorization', 'Bearer ' . $jwt);
            $authenticated = true;
            $activity = null;

            try {
                $this->authenticate($request, $guards);
            } catch (AuthenticationException $e) {

                JsonResponse::deleteCookie('JWT');

                /** @var PersonalAccessToken $personalAccessToken */
                $personalAccessToken = JsonResponse::isRefreshTokenValid($request);

                if ($personalAccessToken) {

                    if (in_array($currentRootName, $exceptionalRouteNames)) {
                        throw new ApiException(AuthErrorCode::REFRESH_TOKEN_IS_STILL_ACTIVE());
                    }

                    if ($currentRootName != $logout) {
                        JsonResponse::refreshToken($personalAccessToken);
                        $activity = 'REFRESH_TOKEN';
                    } else {
                        $authenticated = false;
                    }

                } else {

                    if ($currentRootName == $logout) {
                        throw new ApiException(AuthErrorCode::ALREADY_LOGGED_OUT());
                    }

                    if (!in_array($currentRootName, $exceptionalRouteNames) &&
                        !in_array($currentRootName, $independentRouteNames))
                    {
                        throw new ApiException(AuthErrorCode::UNAUTHORIZED());
                    }

                    $authenticated = false;
                }
            }

            if ($authenticated) {

                if (in_array($currentRootName, $exceptionalRouteNames)) {
                    throw new ApiException(AuthErrorCode::ALREADY_LOGGED_IN());
                }

                JsonResponse::checkUserAccess($request, $activity);
            }

        } else {

            /** @var PersonalAccessToken $personalAccessToken */
            $personalAccessToken = JsonResponse::isRefreshTokenValid($request);

            if ($personalAccessToken) {

                if (in_array($currentRootName, $exceptionalRouteNames)) {
                    throw new ApiException(AuthErrorCode::REFRESH_TOKEN_IS_STILL_ACTIVE());
                }

                if ($currentRootName != $logout) {
                    JsonResponse::refreshToken($personalAccessToken);
                    JsonResponse::checkUserAccess($request, 'REFRESH_TOKEN');
                }

            } else {

                if ($currentRootName == $logout) {
                    throw new ApiException(AuthErrorCode::ALREADY_LOGGED_OUT());
                }

                if (!in_array($currentRootName, $exceptionalRouteNames) &&
                    !in_array($currentRootName, $independentRouteNames))
                {
                    throw new ApiException(AuthErrorCode::UNAUTHORIZED());
                }
            }
        }

        return $next($request);
    }
}
