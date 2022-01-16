<?php

namespace App\Http\Middleware\Authentication;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Models\Device;
use App\Models\PersonalAccessToken;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Klasa przeprowadzająca proces uwierzytelnienia użytkownika
 */
class Authenticate extends Middleware
{
    /**
     * @param Request $request
     * 
     * @return void
     */
    protected function redirectTo($request): void {

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

            if ($personalAccessToken && Validation::timeComparison($personalAccessToken->created_at, env('REFRESH_TOKEN_LIFETIME'), '<=')) {

                if ($uuid = $request->cookie(env('UUID_COOKIE_NAME'))) {
                    $encryptedUuid = $encrypter->encrypt($uuid);
                    $encryptedIp = $encrypter->encrypt($request->ip(), 15);
    
                    /** @var Device $device */
                    $device = Device::where([
                        'uuid' => $encryptedUuid,
                        'ip' => $encryptedIp
                    ])->first();

                    if ($device) {
                        $request->merge(['device_id' => $device->id]);
                    }
                }

                Auth::loginUsingId($personalAccessToken->tokenable_id);
                $personalAccessToken->delete();

                /** @var \App\Models\User $user */
                $user = Auth::user();
                $user->checkAccess($request);
                $user->checkDevice($request, 'TOKEN_REFRESHING');
                $user->createTokens();
            }
        }
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param array $guards
     */
    public function handle($request, Closure $next, ...$guards) {

        if ($jwt = $request->cookie(env('JWT_COOKIE_NAME'))) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);

            try {
                $this->authenticate($request, $guards);
            } catch (AuthenticationException $e) {
                $this->redirectTo($request);
            }

        } else {
            $this->redirectTo($request);
        }

        return $next($request);
    }
}
