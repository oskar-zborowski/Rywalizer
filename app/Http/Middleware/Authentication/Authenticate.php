<?php

namespace App\Http\Middleware\Authentication;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

/**
 * Klasa przeprowadzająca proces uwierzytelnienia użytkownika
 */
class Authenticate extends Middleware
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    protected function redirectTo($request) {

        if ($request->cookie(env('JWT_COOKIE_NAME'))) {
            JsonResponse::deleteCookie('JWT');
        }

        if ($refreshToken = $request->cookie(env('REFRESH_TOKEN_COOKIE_NAME'))) {

            $encrypter = new Encrypter;
            $encryptedRefreshToken = $encrypter->encrypt($refreshToken);

            /** @var PersonalAccessToken $personalAccessToken */
            $personalAccessToken = PersonalAccessToken::where([
                'tokenable_type' => 'App\Models\User',
                'name' => 'JWT',
                'refresh_token' => $encryptedRefreshToken
            ])->first();

            if ($personalAccessToken && Validation::timeComparison($personalAccessToken->created_at, env('REFRESH_TOKEN_LIFETIME'), '>=')) {

                Auth::loginUsingId($personalAccessToken->tokenable_id);
                $personalAccessToken->delete();

                /** @var \App\Models\User $user */
                $user = Auth::user();
                $user->checkAccess();
                $user->checkDevice($request->device_id, 'TOKEN_REFRESHING');
                $user->createTokens();
            }
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param array $guards
     */
    public function handle($request, Closure $next, ...$guards) {

        /** @var \Illuminate\Http\Request $request */

        if ($jwt = $request->cookie(env('JWT_COOKIE_NAME'))) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
            $this->authenticate($request, $guards);
        } else {
            $this->redirectTo($request);
        }

        return $next($request);
    }
}
