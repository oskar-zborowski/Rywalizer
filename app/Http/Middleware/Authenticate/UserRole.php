<?php

namespace App\Http\Middleware\Authenticate;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Validation\Validation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Permissions;
use App\Http\Permissions\RolePermission;

/**
 * Klasa wywoływana w celu sprawdzenia roli użytkownika i przyznania dostępu
 */
class UserRole
{
    /**
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next) {

        /** @var User $user */
        $user = Auth::user();

        $roleType = explode(',', $user->roleType);
        $roleTypeAccessLevel = explode(':', $roleType[1]);
        $detailedUserAccessLevel = Validation::getStringBetweenOthers($roleTypeAccessLevel[1], '"', '"');
        
        if (!$this->checkUserAccess($request->url(), $detailedUserAccessLevel)) {
            throw new ApiException(BaseErrorCode::PERMISSION_DENIED());
        }

        return $next($request);
    }

    /**
     * Metoda wskazuje minimalny poziom dostępu dla podanego endpointu
     * 
     * @param string $endpoint
     * @param string $detailedUserAccessLevel dla specyficznych przypadków cyfra + litera
     * 
     * @return bool
     */
    private function checkUserAccess(string $endpoint, string $detailedUserAccessLevel): bool {

        $userAccessLevel = 0;
        $detailedUserAccessLevelLength = strlen($detailedUserAccessLevel);

        for ($i=0; $i<$detailedUserAccessLevelLength; $i++) {

            if (!is_numeric($detailedUserAccessLevel[$i])) {
                break;
            }

            $userAccessLevel *= 10;
            $userAccessLevel += (int) $detailedUserAccessLevel[$i];
        }

        $endpointRootPos = strpos($endpoint, env('APP_URL')) + strlen(env('APP_URL'));
        $endpoint = substr($endpoint, $endpointRootPos);

        $rolePermissions = RolePermission::getMinimumAccessLevel();
        $access = false;

        for ($i=$userAccessLevel; $i>0; $i--) {

            if (isset($rolePermissions[$i])) {

                foreach ($rolePermissions[$i] as $rP) {
                    if ($rP == $endpoint) {
                        $access = true;
                        break;
                    }
                }

                if ($access) {
                
                    if (isset($rolePermissions['exceptions'][$detailedUserAccessLevel])) {
                        foreach ($rolePermissions['exceptions'][$detailedUserAccessLevel] as $rP) {
                            if ($rP == $endpoint) {
                                $access = false;
                                break;
                            }
                        }
                    }
    
                    break;
                }
            }
        }

        return $access;
    }
}
