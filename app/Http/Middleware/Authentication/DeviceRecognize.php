<?php

namespace App\Http\Middleware\Authentication;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Responses\JsonResponse;
use App\Models\Device;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Klasa identyfikująca urządzenie i zapisująca odpowiednie logi w bazie danych
 */
class DeviceRecognize
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next) {

        $routeName = Route::currentRouteName();

        $exceptionalRouteNames = [
            'auth-handleProviderCallback'
        ];

        if (!in_array($routeName, $exceptionalRouteNames)) {

            $deviceOsName = (bool) $request->os_name;
            $deviceOsVersion = (bool) $request->os_version;
            $deviceBrowserName = (bool) $request->browser_name;
            $deviceBrowserVersion = (bool) $request->browser_version;

            $encrypter = new Encrypter;

            if ($uuid = $request->cookie(env('UUID_COOKIE_NAME'))) {

                $encryptedUuid = $encrypter->encrypt($uuid);
                $encryptedIp = $encrypter->encrypt($request->ip(), 15);

                /** @var Device $device */
                $device = Device::where([
                    'uuid' => $encryptedUuid,
                    'ip' => $encryptedIp
                ])->first();

                if ($device) {
                    $deviceOsName &= $request->os_name != $device->os_name;
                    $deviceOsVersion &= $request->os_version != $device->os_version;
                    $deviceBrowserName &= $request->browser_name != $device->browser_name;
                    $deviceBrowserVersion &= $request->browser_version != $device->browser_version;
                }

                /** @var Device $deviceWithOnlyUuid */
                $deviceWithOnlyUuid = Device::where([
                    'uuid' => $encryptedUuid
                ])->first();
            }

            if ((!isset($device) || !$device) && (!isset($deviceWithOnlyUuid) || !$deviceWithOnlyUuid)) {
                $uuid = $encrypter->generateToken(64, Device::class, 'uuid');
            }

            $updatedInformation = [];

            if ($deviceOsName) {
                $updatedInformation['os_name'] = $request->os_name;
            }

            if ($deviceOsVersion) {
                $updatedInformation['os_version'] = $request->os_version;
            }

            if ($deviceBrowserName) {
                $updatedInformation['browser_name'] = $request->browser_name;
            }

            if ($deviceBrowserVersion) {
                $updatedInformation['browser_version'] = $request->browser_version;
            }

            if (!isset($device)) {
                /** @var Device $device */
                $device = new Device;
                $device->uuid = $uuid;
                $device->ip = $request->ip();
                $device->save();
                $device->update($updatedInformation);
            } else {
                $device->update($updatedInformation);
            }

            $request->merge(['device_id' => $device->id]);

            $this->fillInDeviceData($request);

            JsonResponse::setCookie($uuid, 'UUID');
        }

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

            $encrypter = new Encrypter;
            $encryptedTempUuid = $encrypter->encrypt($tempUuid);

            /** @var \App\Models\Device $tempDevice */
            $tempDevice = Device::where('uuid', $encryptedTempUuid)->first();

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
