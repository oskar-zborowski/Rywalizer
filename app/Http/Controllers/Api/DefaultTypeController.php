<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Responses\JsonResponse;
use App\Models\AccountActionType;
use App\Models\GenderType;
use App\Models\ProviderType;
use App\Models\RoleType;
use Illuminate\Support\Facades\Auth;

class DefaultTypeController extends Controller
{
    /**
     * #### `GET` `/api/provider/types`
     * Pobranie listy zewnętrznych serwisów uwierzytelniających
     * 
     * @return void
     */
    public function getProviderTypes(): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user) {

            if ($user->roleType()->first()->name != 'ADMIN') {
                throw new ApiException(BaseErrorCode::PERMISSION_DENIED());
            }

            if (!$user->hasVerifiedEmail()) {
                throw new ApiException(
                    BaseErrorCode::PERMISSION_DENIED(),
                    'Your email address is not verified.'
                );
            }

            /** @var ProviderType $providerTypes */
            $providerTypes = ProviderType::get();

            $result = null;

            foreach ($providerTypes as $pT) {
                $result[] = $pT->detailedInformation();
            }

        } else {
            $result = ProviderType::where('is_enabled', true)->get();
        }

        JsonResponse::sendSuccess(['providerTypes' => $result]);
    }

    /**
     * #### `GET` `/api/gender/types`
     * Pobranie listy płci
     * 
     * @return void
     */
    public function getGenderTypes(): void {

        /** @var GenderType $genderTypes */
        $genderTypes = GenderType::get();

        JsonResponse::sendSuccess(['genderTypes' => $genderTypes]);
    }

    /**
     * #### `GET` `/api/role/types`
     * Pobranie listy ról w serwisie
     * 
     * @return void
     */
    public function getRoleTypes(): void {

        /** @var RoleType $roleTypes */
        $roleTypes = RoleType::get();

        JsonResponse::sendSuccess(['roleTypes' => $roleTypes]);
    }

    /**
     * #### `GET` `/api/account-action/types`
     * Pobranie listy ze wszystkimi akcjami jakie można wykonać na koncie, np. blokada konta
     * 
     * @return void
     */
    public function getAccountActionTypes(): void {

        /** @var AccountActionType $accountActionTypes */
        $accountActionTypes = AccountActionType::get();

        JsonResponse::sendSuccess(['accountActionTypes' => $accountActionTypes]);
    }
}
