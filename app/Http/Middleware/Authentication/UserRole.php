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

        /** @var \App\Models\DefaultType $permission */
        $permission = Validation::getDefaultType($currentRootName, 'API_PERMISSION');

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user) {
            $role = $user->role()->first();
        } else {
            /** @var \App\Models\DefaultType $role */
            $role = Validation::getDefaultType('GUEST', 'ROLE');
        }

        if (!$role->is_active) {
            throw new ApiException(
                BaseErrorCode::PERMISSION_DENIED(),
                'Inactive role (' . $role->name . ').'
            );
        }

        if (!$permission->is_active) {
            throw new ApiException(
                BaseErrorCode::PERMISSION_DENIED(),
                'Inactive permission (' . $permission->name . ').'
            );
        }

        /** @var \App\Models\RolePermission $rolePermission */
        $rolePermission = $role->rolePermissionsByRole()->where('permission_id', $permission->id)->first();

        if (!$rolePermission) {
            throw new ApiException(BaseErrorCode::PERMISSION_DENIED());
        }

        return $next($request);
    }
}
