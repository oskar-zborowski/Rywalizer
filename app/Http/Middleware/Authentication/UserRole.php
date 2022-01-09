<?php

namespace App\Http\Middleware\Authentication;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Validation\Validation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/**
 * Klasa przeprowadzająca proces autoryzacji użytkownika
 */
class UserRole
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next) {

        $currentRootName = Route::currentRouteName();
        $defaultTypeName = Validation::getDefaultTypeName('PERMISSION');

        if (!$defaultTypeName) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid default type name (PERMISSION).'
            );
        }

        /** @var \App\Models\DefaultType $permission */
        $permission = $defaultTypeName->defaultTypes()->where('name', $currentRootName)->first();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $role = $user->role();
        } else {

            $defaultTypeName = Validation::getDefaultTypeName('ROLE');

            if (!$defaultTypeName) {
                throw new ApiException(
                    BaseErrorCode::INTERNAL_SERVER_ERROR(),
                    'Invalid default type name (ROLE).'
                );
            }

            /** @var \App\Models\DefaultType $role */
            $role = $defaultTypeName->defaultTypes()->where('name', 'GUEST')->first();
        }

        /** @var \App\Models\RolePermission $rolePermission */
        $rolePermission = $role->rolePermissionsByRole()->where('permission_id', $permission->id)->first();

        if (!$rolePermission) {
            throw new ApiException(BaseErrorCode::PERMISSION_DENIED());
        }

        return $next($request);
    }
}
