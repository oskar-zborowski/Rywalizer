<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Validation\Validation;
use App\Http\Responses\JsonResponse;
use App\Models\AccountAction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Klasa odpowiedzialna za wszelkie kwestie związane z operacjami wykonywanymi na koncie
 */
class AccountController extends Controller
{
    /**
     * #### `POST` `/api/v1/account/password`
     * Proces utworzenia niezbędnych danych do przeprowadzenia resetu hasła
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function forgotPassword(Request $request): void {
        /** @var User $user */
        $user = User::where('email', $request->email)->first();
        $user->forgotPassword();
    }

    /**
     * #### `PATCH` `/api/v1/account/password`
     * Proces resetu hasła
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function resetPassword(Request $request): void {

        $accountOperationType = Validation::getAccountOperationType('PASSWORD_RESET');

        if (!$accountOperationType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid account operation type.'
            );
        }

        /** @var \App\Models\AccountOperation $accountOperation */
        $accountOperation = $accountOperationType->accountsOperations()->where('token', $request->token)->first();

        if (!$accountOperation) {
            throw new ApiException(AuthErrorCode::INVALID_PASSWORD_RESET_TOKEN());
        }

        /** @var User $user */
        $user = $accountOperation->operationable()->first();
        $user->resetPassword($request, $accountOperation);
    }

    /**
     * #### `DELETE` `/api/v1/account`
     * Proces przywrócenia usuniętego konta
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function deleteAccount(Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->password, $user->getAuthPassword())) {
            throw new ApiException(AuthErrorCode::INVALID_CREDENTIALS());
        }

        $accountActionType = Validation::getAccountActionType('ACCOUNT_DELETION');

        if (!$accountActionType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid account action type.'
            );
        }

        $isDeleted = $user->actionables()->where('account_action_type_id', $accountActionType->id)->first();

        if ($isDeleted) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Konto już zostało przekazane do usunięcia.'
            );
        }

        $expirationDate = date('Y-m-d H:i:s', strtotime('+' . $accountActionType->period . ' seconds', strtotime(now())));

        /** @var \App\Models\AccountAction $accountAction */
        $accountAction = new AccountAction;
        $accountAction->actionable_type = 'App\Models\User';
        $accountAction->actionable_id = $user->id;
        $accountAction->account_action_type_id = $accountActionType->id;
        $accountAction->expires_at = $expirationDate;
        $accountAction->creator_id = $user->id;
        $accountAction->editor_id = $user->id;
        $accountAction->save();

        JsonResponse::deleteCookie('JWT');
        JsonResponse::deleteCookie('REFRESH-TOKEN');
        JsonResponse::sendSuccess();
    }

    /**
     * #### `PATCH` `/api/v1/account/restore`
     * Proces przywrócenia usuniętego konta
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function restoreAccount(Request $request): void {

        $accountOperationType = Validation::getAccountOperationType('ACCOUNT_RESTORATION');

        if (!$accountOperationType) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid account operation type.'
            );
        }

        /** @var \App\Models\AccountOperation $accountOperation */
        $accountOperation = $accountOperationType->accountsOperations()->where('token', $request->token)->first();

        if (!$accountOperation) {
            throw new ApiException(AuthErrorCode::INVALID_RESTORE_ACCOUNT_TOKEN());
        }

        /** @var User $user */
        $user = $accountOperation->operationable()->first();
        $user->restoreAccount($accountOperation);
    }
}
