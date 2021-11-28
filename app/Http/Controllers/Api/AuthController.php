<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Requests\Auth\FillMissingUserInfoRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\JsonResponse;
use App\Mail\PasswordReset as MailPasswordReset;
use App\Mail\VerificationEmail;
use App\Models\EmailVerification;
use App\Models\ExternalAuthentication;
use App\Models\PasswordReset;
use App\Models\PersonalAccessToken;
use App\Models\ProviderType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

/**
 * Klasa odpowiedzialna za wszelkie kwestie związane z uwierzytelnianiem i jego pochodnymi
 */
class AuthController extends Controller
{
    /**
     * #### `POST` `/api/login`
     * Logowanie użytkownika
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function login(Request $request): void {

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw new ApiException(AuthErrorCode::INVALID_CREDENTIALS());
        }

        JsonResponse::checkUserAccess();

        $this->checkMissingUserInfo(true);
    }

    /**
     * #### `POST` `/api/register`
     * Rejestracja użytkownika
     * 
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function register(RegisterRequest $request, Encrypter $encrypter): void {

        $plainEmail = $encrypter->decrypt($request->email);
        $encryptedPassword = $encrypter->hash($request->password);

        $request->merge(['email' => $plainEmail]);
        $request->merge(['password' => $encryptedPassword]);

        $user = User::create($request->all());

        Auth::loginUsingId($user->id);

        $this->sendVerificationEmail(true);
        $this->checkMissingUserInfo(true);
    }

    /**
     * #### `POST` `/api/forgot-password`
     * Wysyłka linku na maila do resetu hasła
     * 
     * @param Illuminate\Http\Request $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function forgotPassword(Request $request, Encrypter $encrypter): void {

        /** @var User $user */
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            throw new ApiException(
                BaseErrorCode::FAILED_VALIDATION(),
                __('passwords.user')
            );
        }

        /** @var PasswordReset $passwordReset */
        $passwordReset = $user->passwordReset()->first();

        $emailSendingCounter = 1;

        if ($passwordReset) {

            $emailSendingCounter += $passwordReset->email_sending_counter;

            $now = date('Y-m-d H:i:s');
            $waitingDate = date('Y-m-d H:i:s', strtotime('+' . env('PAUSE_BEFORE_RETRYING')*60 . ' seconds', strtotime($passwordReset->updated_at)));
    
            if ($now <= $waitingDate) {
                throw new ApiException(AuthErrorCode::WAIT_BEFORE_RETRYING());
            }

            if ($emailSendingCounter > 255) {
                $emailSendingCounter = 1;
                $passwordReset->delete();
            }
        }

        $plainToken = $encrypter->generatePlainToken(64);

        $user->passwordReset()->updateOrCreate([],
        [
            'token' => $plainToken,
            'email_sending_counter' => $emailSendingCounter
        ]);

        $url = env('APP_URL') . '/reset-password?token=' . $plainToken; // TODO Poprawić na prawidłowy URL

        Mail::to($user)->send(new MailPasswordReset($url));

        JsonResponse::sendSuccess();
    }

    /**
     * #### `PUT` `/api/reset-password`
     * Reset hasła
     * 
     * @param Illuminate\Http\Request $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function resetPassword(Request $request, Encrypter $encrypter): void {

        $encryptedToken = $encrypter->encryptToken($request->token);

        /** @var PasswordReset $passwordReset */
        $passwordReset = PasswordReset::where('token', $encryptedToken)->first();

        if (!$passwordReset) {
            throw new ApiException(AuthErrorCode::INVALID_PASSWORD_RESET_TOKEN());
        }

        $now = date('Y-m-d H:i:s');
        $expirationDate = date('Y-m-d H:i:s', strtotime('+' . env('EMAIL_TOKEN_LIFETIME') . ' minutes', strtotime($passwordReset->updated_at)));

        if ($now > $expirationDate) {
            throw new ApiException(AuthErrorCode::PASSWORD_RESET_TOKEN_HAS_EXPIRED());
        }

        $encryptedPassword = $encrypter->hash($request->password);

        $passwordReset->user()->first()->update(['password' => $encryptedPassword]);
        $passwordReset->delete();

        JsonResponse::sendSuccess();
    }

    /**
     * #### `GET` `/api/email/verification-notification`
     * Wysyłka linku aktywacyjnego na maila
     * 
     * @param bool $afterRegistartion flaga z informacją czy wywołanie metody jest pochodną procesu rejestracji
     * 
     * @return void
     */
    public function sendVerificationEmail(bool $afterRegistartion = false): void {

        /** @var User $user */
        $user = Auth::user();

        $emailSendingCounter = 1;

        if (!$afterRegistartion) {

            if ($user->hasVerifiedEmail()) {
                throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
            }

            /** @var EmailVerification $emailVerification */
            $emailVerification = $user->emailVerification()->first();

            if ($emailVerification) {

                $emailSendingCounter += $emailVerification->email_sending_counter;

                $now = date('Y-m-d H:i:s');
                $waitingDate = date('Y-m-d H:i:s', strtotime('+' . env('PAUSE_BEFORE_RETRYING')*60 . ' seconds', strtotime($emailVerification->updated_at)));
        
                if ($now <= $waitingDate) {
                    throw new ApiException(AuthErrorCode::WAIT_BEFORE_RETRYING());
                }

                if ($emailSendingCounter > 255) {
                    $emailSendingCounter = 1;
                    $emailVerification->delete();
                }
            }
        }

        $encrypter = new Encrypter;
        $plainToken = $encrypter->generatePlainToken(64);

        $user->emailVerification()->updateOrCreate([],
        [
            'token' => $plainToken,
            'email_sending_counter' => $emailSendingCounter
        ]);

        $url = env('APP_URL') . '/email/verify?token=' . $plainToken; // TODO Poprawić na prawidłowy URL

        Mail::to($user)->send(new VerificationEmail($url));

        if (!$afterRegistartion) {
            JsonResponse::sendSuccess();
        }
    }

    /**
     * #### `PUT` `/api/email/verify`
     * Weryfikacja maila
     * 
     * @param Illuminate\Http\Request $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function verifyEmail(Request $request, Encrypter $encrypter): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
        }

        if (!$request->token) {
            throw new ApiException(AuthErrorCode::INVALID_EMAIL_VERIFIFICATION_TOKEN());
        }

        $encryptedToken = $encrypter->encryptToken($request->token);

        /** @var EmailVerification $emailVerification */
        $emailVerification = $user->emailVerification()->where('token', $encryptedToken)->first();

        if (!$emailVerification) {
            throw new ApiException(AuthErrorCode::INVALID_EMAIL_VERIFIFICATION_TOKEN());
        }

        $now = date('Y-m-d H:i:s');
        $expirationDate = date('Y-m-d H:i:s', strtotime('+' . env('EMAIL_TOKEN_LIFETIME') . ' minutes', strtotime($emailVerification->updated_at)));

        if ($now > $expirationDate) {
            throw new ApiException(AuthErrorCode::EMAIL_VERIFIFICATION_TOKEN_HAS_EXPIRED());
        }

        $user->markEmailAsVerified();
        $emailVerification->delete();

        $this->checkMissingUserInfo();
    }

    /**
     * #### `DELETE` `/api/logout`
     * Wylogowanie użytkownika
     * 
     * @param Illuminate\Http\Request $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function logout(Request $request, Encrypter $encrypter): void {

        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();

            JsonResponse::deleteCookie('JWT');
            JsonResponse::deleteCookie('REFRESH-TOKEN');
            JsonResponse::sendSuccess();
        }

        if ($plainRefreshToken = $request->cookie('REFRESH-TOKEN')) {

            $refreshToken = $encrypter->encryptToken($plainRefreshToken);

            /** @var PersonalAccessToken $personalAccessToken */
            $personalAccessToken = PersonalAccessToken::where('refresh_token', $refreshToken)->first();

            if ($personalAccessToken) {
                $personalAccessToken->delete();
            }

            JsonResponse::deleteCookie('REFRESH-TOKEN');
        }

        JsonResponse::deleteCookie('JWT');
        JsonResponse::sendSuccess();
    }

    /**
     * #### `DELETE` `/api/logout-other-devices`
     * Wylogowanie użytkownika ze wszystkich urządzeń poza obecnym
     * 
     * @return void
     */
    public function logoutOtherDevices(): void {

        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        JsonResponse::prepareCookies();
        JsonResponse::sendSuccess();
    }

    /**
     * #### `GET` `/api/auth/{provider}/redirect`
     * Przekierowanie użytkownika do zewnętrznego serwisu uwierzytelniającego (FACEBOOK, GOOGLE)
     *
     * @param string $provider nazwa zewnętrznego serwisu
     * 
     * @return Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider(string $provider): RedirectResponse {

        $this->validateProvider($provider);

        /** @var \Laravel\Socialite\Two\AbstractProvider */
        $driver = Socialite::driver($provider);

        return $driver->stateless()->redirect();
    }

    /**
     * #### `GET` `/api/auth/{provider}/callback`
     * Odebranie informacji o użytkowniku od zewnętrznego serwisu uwierzytelniającego
     *
     * @param string $provider nazwa zewnętrznego serwisu
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function handleProviderCallback(string $provider, Encrypter $encrypter): void {

        /** @var ProviderType $providerType */
        $providerType = $this->validateProvider($provider);

        /** @var \Laravel\Socialite\Two\AbstractProvider */
        $driver = Socialite::driver($provider);

        $user = $driver->stateless()->user();

        $authenticationId = (strlen($user->getId()) > 0 && strlen($user->getId()) < 255) ? $user->getId() : null;
        $encryptedAuthenticationId = $encrypter->encrypt($authenticationId, 254);

        if (!$authenticationId) {
            throw new ApiException(
                AuthErrorCode::INVALID_CREDENTIALS_PROVIDED(),
                __('validation.custom.invalid-provider-id'),
            );
        }

        /** @var ExternalAuthentication $externalAuthentication */
        $externalAuthentication = $providerType->externalAuthentication()->where('authentication_id', $encryptedAuthenticationId)->first();

        if (!$externalAuthentication) {

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {

                $encryptedEmail = $encrypter->encrypt($user->getEmail(), 254);

                /** @var User $userExist */
                $userExist = User::where('email', $encryptedEmail)->first();

                if ($userExist) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['email' => [__('validation.unique', ['attribute' => 'email'])]]
                    );
                }
            }

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

            if (strlen($user->getAvatar())) { //TODO Sprawdzić wariant co jest zwracane kiedy użytkownik nie ma ustawionego zdjęcia profilowego
                $avatarFilename = $this->saveAvatar($provider, $user->getAvatar());
            }

            $newUser = [
                'first_name' => $firstName,
                'last_name' => $lastName
            ];

            if (isset($encryptedEmail)) {
                $newUser['email'] = $user->getEmail();
            }

            if (isset($avatarFilename)) {
                $newUser['avatar'] = $avatarFilename;
            }

            /** @var User $createUser */
            $createUser = User::create($newUser);

            $createUser->externalAuthentication()->create([
                'authentication_id' => $authenticationId,
                'provider_type_id' => $providerType->id
            ]);

            Auth::loginUsingId($createUser->id);

            /** @var User $user */
            $user = Auth::user();

            if ($user->email) {
                $user->markEmailAsVerified();
            }

        } else {
            Auth::loginUsingId($externalAuthentication->user_id);
            JsonResponse::checkUserAccess();
        }

        $this->checkMissingUserInfo(true);
    }

    /**
     * #### `POST` `/api/fill-missing-user-info`
     * Uzupełnienie brakujących informacji o użytkowniku
     * 
     * @param App\Http\Requests\Auth\FillMissingUserInfoRequest $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function fillMissingUserInfo(FillMissingUserInfoRequest $request, Encrypter $encrypter): void {

        /** @var User $user */
        $user = Auth::user();

        if (!$user->email && $request->email) {

            $encryptedEmail = $encrypter->encrypt($request->email, 254);

            /** @var User $userExist */
            $userExist = User::where('email', $encryptedEmail)->first();

            if ($userExist) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    ['email' => [__('validation.unique', ['attribute' => 'email'])]]
                );
            }
        }

        $supplementaryInfo = null;

        if (!$user->email && $request->email) {
            $supplementaryInfo['email'] = $request->email;
        }

        if (!$user->birth_date && $request->birth_date) {
            $supplementaryInfo['birth_date'] = $request->birth_date;
        }

        if (!$user->gender_type_id && $request->gender_type_id) {
            $supplementaryInfo['gender_type_id'] = $request->gender_type_id;
        }

        if (!$user->avatar && $request->avatar) {
            // TODO Zrobić wgrywanie i zapisywanie zdjęć przez formularz (wykorzystać metodę saveAvatar)
            $supplementaryInfo['avatar'] = $request->avatar;
        }

        if ($supplementaryInfo) {
            $user->update($supplementaryInfo);
        }

        $user->refresh();

        if (isset($supplementaryInfo['email'])) {
            $this->sendVerificationEmail(true);
        }

        $this->checkMissingUserInfo();
    }

    /**
     * #### `GET` `/api/user`
     * Pobranie informacji o użytkowniku
     * 
     * @return void
     */
    public function user(): void {
        $this->checkMissingUserInfo();
    }

    /**
     * Sprawdzenie czy dany serwis uwierzytelniający jest dostępny
     * 
     * @param string $provider nazwa zewnętrznego serwisu
     * 
     * @return App\Models\ProviderType
     */
    private function validateProvider(string $provider): ProviderType {

        $encrypter = new Encrypter;
        $provider = strtoupper($provider);
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

    /**
     * Zapisanie na serwerze avatara użytkownika pobranego z serwisu uwierzytelniającego
     * 
     * @param string $provider nazwa zewnętrznego serwisu
     * @param string $avatarUrl adres URL do zdjecia profilowego z serwisu uwierzytelniającego
     * 
     * @return string
     */
    private function saveAvatar(string $provider, string $avatarUrl): string {

        $provider = strtoupper($provider);

        switch ($provider) {

            case 'FACEBOOK':
            case 'GOOGLE':
                $avatarUrlHeaders = get_headers($avatarUrl, 1);
                $avatarUrlLocation = isset($avatarUrlHeaders['Location']) ? $avatarUrlHeaders['Location'] : $avatarUrl;
                $avatarContentType = $avatarUrlHeaders['Content-Type'];

                if (is_array($avatarContentType)) {
                    $avatarContentType = $avatarContentType[0];
                }

                $avatarFileExtensionSeparators = explode('/', $avatarContentType);
                $avatarFileExtensionSeparatorsLength = count($avatarFileExtensionSeparators);
                $avatarFileExtension = '.' . $avatarFileExtensionSeparators[$avatarFileExtensionSeparatorsLength-1];
                break;

            default:
                // TODO Uzupełnić zapisywanie zdjęcia z formularza
                break;
        }

        $encrypter = new Encrypter;
    
        do {
            $avatarFilename = $encrypter->generatePlainToken(64, $avatarFileExtension);
            $avatarFilenameEncrypted = $encrypter->encryptToken($avatarFilename);

            /** @var User $avatarExists */
            $avatarExists = User::where('avatar', $avatarFilenameEncrypted)->first();
        } while ($avatarExists);

        $avatarContents = file_get_contents($avatarUrlLocation);
        Storage::put('avatars/' . $avatarFilename, $avatarContents);

        return $avatarFilename;
    }

    /**
     * Sprawdzenie brakujących informacji o użytkowniku i zwrócenie obiektu użytkownika
     * 
     * @param bool $withTokens flaga określająca czy mają zostać utworzone tokeny autoryzacyjne
     * 
     * @return void
     */
    private function checkMissingUserInfo($withTokens = false): void {

        /** @var User $user */
        $user = Auth::user();

        $missingInfo = null;

        if (!$user->email) {
            $missingInfo['required']['email'] = [__('validation.custom.is-missing', ['attribute' => 'email'])];
        }

        if (!$user->birth_date) {
            $missingInfo['required']['birth_date'] = [__('validation.custom.is-missing', ['attribute' => 'birthDate'])];
        }

        if (!$user->avatar) {
            $missingInfo['optional']['avatar'] = [__('validation.custom.is-missing', ['attribute' => 'avatar'])];
        }

        if (!$user->gender_type_id) {
            $missingInfo['optional']['gender_type_id'] = [__('validation.custom.is-missing', ['attribute' => 'genderTypeId'])];
        }

        if ($withTokens) {
            JsonResponse::prepareCookies();
        }

        if (isset($missingInfo['required']) || !$user->email_verified_at) {
            throw new ApiException(
                $user->email_verified_at ? AuthErrorCode::MISSING_USER_INFORMATION() : AuthErrorCode::UNVERIFIED_EMAIL(),
                ['user' => $user],
                ['missing_user_information' => $missingInfo]
            );
        }

        JsonResponse::sendSuccess(
            ['user' => $user],
            ['missing_user_information' => $missingInfo]
        );
    }
}
