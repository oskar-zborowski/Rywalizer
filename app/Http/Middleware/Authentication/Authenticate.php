<?php

namespace App\Http\Middleware\Authentication;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Models\Device;
use App\Models\PersonalAccessToken;
use Closure;
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
     * @param Request $request
     * @param Closure $next
     * @param array $guards
     */
    public function handle($request, Closure $next, ...$guards) {

        if ($jwt = $request->cookie(env('JWT_COOKIE_NAME'))) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
            $this->authenticate($request, $guards);
        } else {
            $this->redirectTo($request);
        }

        $this->fillInDeviceData($request);

        return $next($request);
    }

    /**
     * Uzupełnienie logów dla logowania, bądź rejestracji poprzez OAuth
     * 
     * @param Request $request
     * 
     * @return void
     */
    private function fillInDeviceData(Request $request): void {

        if ($tempUuid = $request->cookie(env('TEMP_UUID_COOKIE_NAME'))) {

            /** @var \App\Models\Device $device */
            $device = Device::where('id', $request->device_id)->first();

            /** @var \App\Models\Device $tempDevice */
            $tempDevice = Device::where('uuid', $tempUuid)->first();

            if ($device && $tempDevice) {

                /** @var \App\Models\Authentication $autentications */
                $autentications = $tempDevice->authentications()->get();

                /** @var \App\Models\Authentication $a */
                foreach ($autentications as $a) {
                    $a->device_id = $device->id;
                    $a->save();
                }

                $tempDevice->delete();

                JsonResponse::deleteCookie('TEMP_UUID');
            }
        }
    }
}
