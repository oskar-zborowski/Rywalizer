<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Validation\Validation;
use App\Models\User;
use Illuminate\Http\Request;

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
