<?php

namespace App\Http\Middleware\Authentication;

use App\Exceptions\ApiException;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rules\Password;

/**
 * Klasa walidująca pola w przychodzącym żądaniu, powiązane z encją użytkownika
 */
class BeforeUser
{
    /**
     * @param Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next) {

        $routeName = Route::currentRouteName();

        $login = 'auth-login';
        $register = 'auth-register';
        $logoutAll = 'auth-logoutAll';
        $forgotPassword = 'account-forgotPassword';
        $resetPassword = 'account-resetPassword';
        $restoreAccount = 'account-restoreAccount';
        $deleteAccount = 'account-deleteAccount';
        $verifyEmail = 'user-verifyEmail';
        $updateUser = 'user-updateUser';
        $uploadAvatar = 'user-uploadAvatar';
        $changeAvatar = 'user-changeAvatar';

        $encrypter = new Encrypter;

        if ($routeName == $login ||
            $routeName == $register ||
            $routeName == $forgotPassword ||
            $routeName == $updateUser)
        {
            $request->validate([
                'email' => 'nullable|string|email|max:254'
            ]);

            if ($routeName != $updateUser) {
                $request->validate([
                    'email' => 'required'
                ]);
            }

            if ($request->email) {
                $encryptedEmail = $encrypter->encrypt($request->email, 254);
                $request->merge(['email' => $encryptedEmail]);
            }

            if ($routeName == $forgotPassword) {
                $request->validate([
                    'email' => 'exists:users'
                ]);
            }
        }

        if ($routeName == $login ||
            $routeName == $register ||
            $routeName == $logoutAll ||
            $routeName == $resetPassword ||
            $routeName == $deleteAccount ||
            $routeName == $updateUser)
        {
            $request->validate([
                'password' => 'nullable|string|between:8,20'
            ]);

            if ($routeName != $updateUser) {
                $request->validate([
                    'password' => 'required'
                ]);
            }

            if ($routeName == $register ||
                $routeName == $resetPassword ||
                $routeName == $updateUser)
            {
                $request->validate([
                    'password' => ['confirmed', Password::defaults()]
                ]);

                if ($request->password) {
                    $encryptedPassword = $encrypter->hash($request->password);
                    $request->merge(['password' => $encryptedPassword]);
                }
            }
        }

        if ($routeName == $resetPassword ||
            $routeName == $restoreAccount ||
            $routeName == $verifyEmail)
        {
            $request->validate([
                'token' => 'required|string|alpha_num|size:48'
            ]);

            if ($routeName == $resetPassword) {
                $request->validate([
                    'do_not_logout' => 'nullable|boolean'
                ]);
            }

            if ($request->token) {
                $encryptedToken = $encrypter->encrypt($request->token);
                $request->merge(['token' => $encryptedToken]);
            }
        }

        if ($routeName == $register ||
            $routeName == $updateUser)
        {
            if ($request->gender_id) {

                $defaultTypeName = Validation::getDefaultTypeName('GENDER');

                /** @var \App\Models\DefaultType $gender */
                $gender = $defaultTypeName->defaultTypes()->where('id', $request->gender_id)->first();

                if (!$gender) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        'Wybrano nieprawidłową płeć.'
                    );
                }
            }

            if ($routeName == $updateUser) {

                $request->validate([
                    'telephone' => 'nullable|string|max:24'
                ]);
    
                if ($request->telephone) {
                    $encryptedTelephone = $encrypter->encrypt($request->telephone, 24);
                    $request->merge(['telephone' => $encryptedTelephone]);
                }
            }
        }

        if ($routeName == $uploadAvatar) {
            $request->validate([
                'avatar' => 'image|max:2048'
            ]);
        }

        return $next($request);
    }
}
