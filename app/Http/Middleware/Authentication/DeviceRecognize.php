<?php

namespace App\Http\Middleware\Authentication;

use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Responses\JsonResponse;
use App\Models\Device;
use Closure;
use Illuminate\Http\Request;

/**
 * Klasa wywoływana w celu zweryfikowania urządzenia
 */
class DeviceRecognize
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next) {

        $updatedInformation = null;

        $deviceOsName = (bool) $request->os_name;
        $deviceOsVersion = (bool) $request->os_version;
        $deviceBrowserName = (bool) $request->browser_name;
        $deviceBrowserVersion = (bool) $request->browser_version;

        $encrypter = new Encrypter;

        if ($uuid = $request->cookie(env('UUID_COOKIE_NAME'))) {

            $encryptedIp = $encrypter->encrypt($request->ip(), 15);
            $encryptedUuid = $encrypter->encrypt($uuid);

            $device = Device::where([
                'ip' => $encryptedIp,
                'uuid' => $encryptedUuid
            ])->first();

            if ($device) {
                $deviceOsName &= $request->os_name != $device->os_name;
                $deviceOsVersion &= $request->os_version != $device->os_version;
                $deviceBrowserName &= $request->browser_name != $device->browser_name;
                $deviceBrowserVersion &= $request->browser_version != $device->browser_version;
            }
        }

        if (!isset($uuid)) {
            $uuid = $encrypter->generateToken(64, Device::class, 'uuid');
        }

        $updatedInformation['uuid'] = $uuid;
        $updatedInformation['ip'] = $request->ip();

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
            $device = Device::create($updatedInformation); 
        } else if ($updatedInformation) {
            $device->update($updatedInformation);
        }

        $request->merge(['device_id' => $device->id]);

        JsonResponse::setCookie($uuid, 'UUID');
        
        return $next($request);
    }
}