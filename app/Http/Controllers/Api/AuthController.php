<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\JsonResponse;
use App\Mail\EmailVerification as MailEmailVerification;
use App\Models\DefaultType;
use App\Models\PersonalAccessToken;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;

/**
 * Klasa odpowiedzialna za wszelkie kwestie związane z uwierzytelnianiem użytkownika
 */
class AuthController extends Controller
{
    /**
     * #### `POST` `/api/v1/auth/login`
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
        $user->checkDevice($request->device_id, 'LOGIN_FORM');
        $user->checkAccess();
        $user->createTokens();
        $user->getBasicInformation();
    }

    /**
     * #### `POST` `/api/v1/auth/register`
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
        $user->saveAcceptedAgreements($request);
        $user->checkDevice($request->device_id, 'REGISTER_FORM');
        $user->createTokens();
        $user->sendVerificationEmail(true);
        $user->getBasicInformation();
    }

    /**
     * #### `GET` `/api/v1/auth/{provider}/redirect`
     * Przekierowanie użytkownika do zewnętrznego serwisu uwierzytelniającego (FACEBOOK, GOOGLE)
     *
     * @param string $providerName nazwa zewnętrznego serwisu uwierzytelniającego
     * 
     * @return RedirectResponse
     */
    public function redirectToProvider(string $providerName): RedirectResponse {

        $providerName = strtolower($providerName);
        $this->validateProvider($providerName);

        /** @var \Laravel\Socialite\Two\AbstractProvider */
        $driver = Socialite::driver($providerName);

        return $driver->stateless()->redirect();
    }

    /**
     * #### `GET` `/api/v1/auth/{provider}/callback`
     * Odebranie informacji o użytkowniku od zewnętrznego serwisu uwierzytelniającego
     *
     * @param string $providerName nazwa zewnętrznego serwisu uwierzytelniającego
     * @param Encrypter $encrypter
     * 
     * @return void
     */
    public function handleProviderCallback(string $providerName, Encrypter $encrypter): void {

        $providerName = strtolower($providerName);
        $provider = $this->validateProvider($providerName);

        /** @var \Laravel\Socialite\Two\AbstractProvider */
        $driver = Socialite::driver($providerName);
        $user = $driver->stateless()->user();

        $externalAuthenticationId = (strlen($user->getId()) > 0 && strlen($user->getId()) < 256) ? $user->getId() : null;

        if (!$externalAuthenticationId) {
            throw new ApiException(
                AuthErrorCode::INVALID_CREDENTIALS_PROVIDED(),
                __('validation.custom.invalid-provider-id')
            );
        }

        $encryptedExternalAuthenticationId = $encrypter->encrypt($externalAuthenticationId, 255);

        /** @var \App\Models\ExternalAuthentication $externalAuthentication */
        $externalAuthentication = $provider->externalAuthentications()->where('external_authentication_id', $encryptedExternalAuthenticationId)->first();

        if (!$externalAuthentication) {

            $foundUser = null;
            $newUser = null;

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {

                $encryptedEmail = $encrypter->encrypt($user->getEmail(), 254);

                /** @var User $foundUser */
                $foundUser = User::where('email', $encryptedEmail)->first();

                $newUser['email'] = $user->getEmail();
                $newUser['email_verified_at'] = now();

            } else if (strlen($user->getEmail()) > 0 && strlen($user->getEmail()) < 25) {

                $encryptedTelephone = $encrypter->encrypt($user->getEmail(), 24);

                /** @var User $foundUser */
                $foundUser = User::where('telephone', $encryptedTelephone)->first();

                $newUser['telephone'] = $user->getEmail();
                $newUser['telephone_verified_at'] = now();
            }

            if ($foundUser) {

                if (isset($encryptedEmail) && !$foundUser->email_verified_at) {
                    $accountOperationType = Validation::getAccountOperationType('EMAIL_VERIFICATION');
                } else if (isset($encryptedTelephone) && !$foundUser->telephone_verified_at) {
                    $accountOperationType = Validation::getAccountOperationType('TELEPHONE_VERIFICATION');
                }

                if (isset($accountOperationType)) {

                    if (!$accountOperationType) {
                        throw new ApiException(
                            BaseErrorCode::INTERNAL_SERVER_ERROR(),
                            'Invalid account operation type.'
                        );
                    }

                    $foundUser->operationable()->where('account_operation_type_id', $accountOperationType->id)->delete();
                }

            } else {

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
            }

            /** @var User $createdUser */
            $createdUser = User::updateOrCreate([], $newUser);

            $createdUser->externalAuthentications()->create([
                'external_authentication_id' => $externalAuthenticationId,
                'provider_type_id' => $provider->id
            ]);

            Auth::loginUsingId($createdUser->id);

            if (strlen($user->getAvatar()) > 0 && (!$foundUser || !$foundUser->avatar)) {
                // TODO Sprawdzić wariant co jest zwracane kiedy użytkownik nie ma ustawionego zdjęcia profilowego
                $createdUser->saveAvatar($user->getAvatar());
            }

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

        $providerName = strtoupper($providerName);

        if (!isset($foundUser) || $foundUser) {
            $authenticationType = 'LOGIN_' . $providerName;
        } else {
            $authenticationType = 'REGISTER_' . $providerName;
        }

        /** @var User $user */
        $user = Auth::user();
        $user->checkDevice(null, $authenticationType);
        $user->checkAccess();
        $user->createTokens();
        $user->getBasicInformation();
    }

    /**
     * #### `DELETE` `/api/v1/auth/logout`
     * Proces wylogowania użytkownika
     * 
     * @param Request $request
     * @param Encrypter $encrypter
     * 
     * @return void
     */
    public function logout(Request $request, Encrypter $encrypter): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user) {

            /** @var \Laravel\Sanctum\HasApiTokens $userCurrentAccessToken */
            $userCurrentAccessToken = $user->currentAccessToken();
            $userCurrentAccessToken->delete();

            JsonResponse::deleteCookie('JWT');
        }

        if ($refreshToken = $request->cookie(env('REFRESH_TOKEN_COOKIE_NAME'))) {

            $encryptedRefreshToken = $encrypter->encrypt($refreshToken);

            /** @var PersonalAccessToken $userAccessToken */
            $userAccessToken = PersonalAccessToken::where('refresh_token', $encryptedRefreshToken)->first();

            if ($userAccessToken) {
                $userAccessToken->delete();
            }

            JsonResponse::deleteCookie('REFRESH-TOKEN');
        }

        JsonResponse::sendSuccess();
    }

    /**
     * #### `DELETE` `/api/v1/auth/logout/all`
     * Proces wylogowania użytkownika ze wszystkich urządzeń poza obecnym
     * 
     * @param Request $request
     * 
     * @return void
     */
    public function logoutAll(Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if (!Hash::check($request->password, $user->getAuthPassword())) {
            throw new ApiException(AuthErrorCode::INVALID_CREDENTIALS());
        }

        if ($user->currentAccessToken() !== null) {
            /** @var PersonalAccessToken $userAccessToken */
            $userAccessToken = $user->currentAccessToken();
        } else {
            /** @var PersonalAccessToken $userAccessToken */
            $userAccessToken = $user->tokenable()->latest();
        }

        /** @var PersonalAccessToken $userAccessTokens */
        $userAccessTokens = $user->tokenable()->where('id', '<>', $userAccessToken->id);
        $userAccessTokens->delete();

        JsonResponse::sendSuccess();
    }

    /**
     * Sprawdzenie czy dany serwis uwierzytelniający jest dostępny
     * 
     * @param string $providerName nazwa zewnętrznego serwisu
     * 
     * @return DefaultType
     */
    private function validateProvider(string $providerName): DefaultType {

        $defaultTypeName = Validation::getDefaultTypeName('PROVIDER');

        if (!$defaultTypeName) {
            throw new ApiException(
                BaseErrorCode::INTERNAL_SERVER_ERROR(),
                'Invalid default type name.'
            );
        }

        $providerName = strtoupper($providerName);

        /** @var DefaultType $provider */
        $provider = $defaultTypeName->defaultTypes()->where([
            'name' => $providerName,
            'is_enabled' => 1
        ])->first();

        if (!$provider) {
            throw new ApiException(AuthErrorCode::INVALID_PROVIDER());
        }

        return $provider;
    }
}
