<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\ImageProcessing\ImageProcessing;
use App\Http\Libraries\Validation\Validation;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\JsonResponse;
use App\Mail\EmailVerification as MailEmailVerification;
use App\Models\AccountOperation;
use App\Models\ExternalAuthentication;
use App\Models\PersonalAccessToken;
use App\Models\ProviderType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

/**
 * Klasa odpowiedzialna za wszelkie kwestie związane z uwierzytelnianiem
 */
class AuthController extends Controller
{
    /**
     * #### `POST` `/api/auth/login`
     * Proces logowania użytkownika
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function login(Request $request): void {

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw new ApiException(AuthErrorCode::INVALID_CREDENTIALS());
        }

        /** @var User $user */
        $user = Auth::user();
        $user->checkAccess();
        $user->checkDevice($request->device_id, 'LOGIN');
        $user->createTokens();
        $user->checkMissingInformation();
    }

    /**
     * #### `POST` `/api/auth/register`
     * Proces rejestracji nowego użytkownika
     * 
     * @param RegisterRequest $request
     * @param Encrypter $encrypter
     * 
     * @return void
     */
    public function register(RegisterRequest $request, Encrypter $encrypter): void {

        $email = $encrypter->decrypt($request->email);
        $request->merge(['email' => $email]);

        /** @var User $newUser */
        $newUser = User::create($request->only('first_name', 'last_name', 'email', 'password', 'birth_date', 'gender_type_id'));

        Auth::loginUsingId($newUser->id);

        /** @var User $user */
        $user = Auth::user();
        $user->checkDevice($request->device_id, 'REGISTER');
        $user->createTokens();
        $user->sendVerificationEmail(true);
        $user->checkMissingInformation();
    }

    /**
     * #### `POST` `/api/auth/forgot-password`
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
     * #### `PATCH` `/api/auth/reset-password`
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

        /** @var AccountOperation $accountOperation */
        $accountOperation = AccountOperation::where([
            'account_operation_type_id' => $accountOperationType->id,
            'token' => $request->token
        ])->first();

        if (!$accountOperation) {
            throw new ApiException(AuthErrorCode::INVALID_PASSWORD_RESET_TOKEN());
        }

        $accountOperation->user()->first()->resetPassword($request, $accountOperation);
    }

    /**
     * #### `DELETE` `/api/auth/logout/me`
     * Proces wylogowania użytkownika
     * 
     * @param Request $request
     * @param Encrypter $encrypter
     * 
     * @return void
     */
    public function logoutMe(Request $request, Encrypter $encrypter): void {

        $user = $request->user();

        if ($user) {
            $user->currentAccessToken()->delete();
            JsonResponse::deleteCookie('JWT');
        }

        if ($refreshToken = $request->cookie(env('REFRESH_TOKEN_COOKIE_NAME'))) {

            $encryptedRefreshToken = $encrypter->encrypt($refreshToken);

            /** @var PersonalAccessToken $personalAccessToken */
            $personalAccessToken = PersonalAccessToken::where('refresh_token', $encryptedRefreshToken)->first();

            if ($personalAccessToken) {
                $personalAccessToken->delete();
            }

            JsonResponse::deleteCookie('REFRESH-TOKEN');
        }

        JsonResponse::sendSuccess();
    }

    /**
     * #### `DELETE` `/api/auth/logout/other-devices`
     * Proces wylogowania użytkownika ze wszystkich urządzeń poza obecnym
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function logoutOtherDevices(Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->password, $user->getAuthPassword())) {
            throw new ApiException(AuthErrorCode::INVALID_CREDENTIALS());
        }

        if (!isset($user->currentAccessToken()->id)) {
            $userAccessTokenId = $user->personalAccessToken()->latest()->first()->id;
        } else {
            $userAccessTokenId = $user->currentAccessToken()->id;
        }

        /** @var PersonalAccessToken $personalAccessTokens */
        $personalAccessTokens = $user->personalAccessToken()->where('id', '<>', $userAccessTokenId);
        $personalAccessTokens->delete();

        JsonResponse::sendSuccess();
    }

    /**
     * #### `GET` `/api/auth/{provider}/redirect`
     * Przekierowanie użytkownika do zewnętrznego serwisu uwierzytelniającego (FACEBOOK, GOOGLE)
     *
     * @param string $provider nazwa zewnętrznego serwisu uwierzytelniającego
     * 
     * @return RedirectResponse
     */
    public function redirectToProvider(string $provider): RedirectResponse {

        $provider = strtolower($provider);
        $this->validateProvider($provider);

        /** @var \Laravel\Socialite\Two\AbstractProvider */
        $driver = Socialite::driver($provider);

        return $driver->stateless()->redirect();
    }

    /**
     * #### `GET` `/api/auth/{provider}/callback`
     * Odebranie informacji o użytkowniku od zewnętrznego serwisu uwierzytelniającego
     *
     * @param string $provider nazwa zewnętrznego serwisu uwierzytelniającego
     * @param Encrypter $encrypter
     * 
     * @return void
     */
    public function handleProviderCallback(string $provider, Encrypter $encrypter): void {

        $provider = strtolower($provider);

        /** @var ProviderType $providerType */
        $providerType = $this->validateProvider($provider);

        /** @var \Laravel\Socialite\Two\AbstractProvider */
        $driver = Socialite::driver($provider);

        $user = $driver->stateless()->user();

        $externalAuthenticationId = (strlen($user->getId()) > 0 && strlen($user->getId()) < 256) ? $user->getId() : null;
        $encryptedExternalAuthenticationId = $encrypter->encrypt($externalAuthenticationId, 255);

        if (!$externalAuthenticationId) {
            throw new ApiException(
                AuthErrorCode::INVALID_CREDENTIALS_PROVIDED(),
                __('validation.custom.invalid-provider-id')
            );
        }

        /** @var ExternalAuthentication $externalAuthentication */
        $externalAuthentication = $providerType->externalAuthentication()->where('external_authentication_id', $encryptedExternalAuthenticationId)->first();

        if (!$externalAuthentication) {

            $foundUser = null;
            $newUser = null;

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {

                $encryptedEmail = $encrypter->encrypt($user->getEmail(), 254);

                /** @var User $foundUser */
                $foundUser = User::where('email', $encryptedEmail)->first();

            } else if (strlen($user->getEmail()) > 0 && strlen($user->getEmail()) < 25) {

                $encryptedTelephone = $encrypter->encrypt($user->getEmail(), 24);

                /** @var User $foundUser */
                $foundUser = User::where('telephone', $encryptedTelephone)->first();
            }

            if (!$foundUser) {

                $names = explode(' ', $user->getName());
                $namesLength = count($names);

                $firstName = $names[0];

                for ($i=1; $i<$namesLength; $i++) {
                    if ($i == $namesLength-1) {
                        $lastName = $names[$i];
                    } else {
                        $firstName .= ' ' . $names[$i];
                    }
                }

                $newUser['first_name'] = $firstName;
                $newUser['last_name'] = $lastName;

                if (isset($encryptedEmail)) {
                    $newUser['email'] = $user->getEmail();
                } else if (isset($encryptedTelephone)) {
                    $newUser['telephone'] = $user->getEmail();
                }

            } else if (!$foundUser->email_verified_at) {

                if (isset($encryptedEmail)) {

                    $accountOperationType = Validation::getAccountOperationType('EMAIL_VERIFICATION');

                    if (!$accountOperationType) {
                        throw new ApiException(
                            BaseErrorCode::INTERNAL_SERVER_ERROR(),
                            'Invalid account operation type.'
                        );
                    }

                    $foundUser->accountOperation()->where('account_operation_type_id', $accountOperationType->id)->delete();
                }

                $newUser['password'] = null;
            }

            if (isset($encryptedEmail)) {
                $newUser['email_verified_at'] = now();
            }

            if (strlen($user->getAvatar()) > 0 && (!$foundUser || !$foundUser->avatar)) {
                // TODO Sprawdzić wariant co jest zwracane kiedy użytkownik nie ma ustawionego zdjęcia profilowego
                $newUser['avatar'] = ImageProcessing::saveAvatar($user->getAvatar());
            }

            /** @var User $createdUser */
            $createdUser = User::updateOrCreate([], $newUser);

            $createdUser->externalAuthentication()->create([
                'external_authentication_id' => $externalAuthenticationId,
                'provider_type_id' => $providerType->id
            ]);

            Auth::loginUsingId($createdUser->id);

            if ($createdUser->email) {
                if (!$foundUser) {
                    Mail::to($createdUser)->send(new MailEmailVerification());
                } else {
                    // TODO Jakiś inny mail, że dodano możliwość logowania się providerem
                }
            }

        } else {
            Auth::loginUsingId($externalAuthentication->user_id);
        }

        /** @var User $foundUser */
        $foundUser = Auth::user();
        $foundUser->checkAccess();
        $foundUser->createTokens();
        $foundUser->checkMissingInformation();
    }

    /**
     * #### `PATCH` `/api/auth/restore-account`
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

        /** @var AccountOperation $accountOperation */
        $accountOperation = AccountOperation::where([
            'account_operation_type_id' => $accountOperationType->id,
            'token' => $request->token
        ])->first();

        if (!$accountOperation) {
            throw new ApiException(AuthErrorCode::INVALID_RESTORE_ACCOUNT_TOKEN());
        }

        $accountOperation->user()->first()->restoreAccount($accountOperation);
    }

    /**
     * Sprawdzenie czy dany serwis uwierzytelniający jest dostępny
     * 
     * @param string $provider nazwa zewnętrznego serwisu
     * 
     * @return ProviderType
     */
    private function validateProvider(string $provider): ProviderType {

        $provider = strtoupper($provider);
        $encrypter = new Encrypter;
        $encryptedProviderName = $encrypter->encrypt($provider, 9);

        /** @var ProviderType $providerTypes */
        $providerType = ProviderType::where([
            'name' => $encryptedProviderName,
            'is_enabled' => 1
        ])->first();

        if (!$providerType) {
            throw new ApiException(AuthErrorCode::INVALID_PROVIDER());
        }

        return $providerType;
    }
}
