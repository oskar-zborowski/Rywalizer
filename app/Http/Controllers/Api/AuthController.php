<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Validation\Validation;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
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
    
        JsonResponse::checkUserAccess($request);

        $this->checkMissingUserInformation(true);
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
        $request->merge(['email' => $plainEmail]);

        $user = User::create($request->only('first_name', 'last_name', 'email', 'password', 'birth_date', 'gender_type_id'));

        Auth::loginUsingId($user->id);

        $this->sendVerificationEmail(true);
        $this->checkMissingUserInformation(true);
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

        /** @var PasswordReset $passwordReset */
        $passwordReset = $user->passwordReset()->first();

        $emailSendingCounter = 1;

        if ($passwordReset) {

            $emailSendingCounter += $passwordReset->email_sending_counter;

            if (Validation::timeComparison($passwordReset->updated_at, env('PAUSE_BEFORE_RETRYING')*60, '<=', 'seconds')) {
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
     * #### `PATCH` `/api/reset-password`
     * Reset hasła
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function resetPassword(Request $request): void {

        /** @var PasswordReset $passwordReset */
        $passwordReset = PasswordReset::where('token', $request->token)->first();

        if (Validation::timeComparison($passwordReset->updated_at, env('EMAIL_TOKEN_LIFETIME'), '>')) {
            throw new ApiException(AuthErrorCode::PASSWORD_RESET_TOKEN_HAS_EXPIRED());
        }

        $passwordReset->user()->first()->update([
            'password' => $request->password,
            'last_time_password_changed' => now()
        ]);

        if ($request->do_not_logout === false) {
            PersonalAccessToken::where('tokenable_id', $passwordReset->user_id)->delete();
        }

        $passwordReset->delete();

        JsonResponse::sendSuccess();
    }

    /**
     * #### `POST` `/api/email/verification-notification`
     * Wysyłka linku aktywacyjnego na maila
     * 
     * @param bool $afterRegistartion flaga z informacją czy wywołanie metody jest pochodną procesu rejestracji
     * 
     * @return void
     */
    public function sendVerificationEmail(bool $afterRegistartion = false): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->email) {

            $emailSendingCounter = 1;

            if (!$afterRegistartion) {
    
                if ($user->hasVerifiedEmail()) {
                    throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
                }
    
                /** @var EmailVerification $emailVerification */
                $emailVerification = $user->emailVerification()->first();
    
                if ($emailVerification) {
    
                    $emailSendingCounter += $emailVerification->email_sending_counter;
            
                    if (Validation::timeComparison($emailVerification->updated_at, env('PAUSE_BEFORE_RETRYING')*60, '<=', 'seconds')) {
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

        } else {
            throw new ApiException(
                AuthErrorCode::INVALID_CREDENTIALS_PROVIDED(),
                ['email' => [__('validation.custom.is-missing', ['attribute' => 'adres email'])]]
            );
        }
    }

    /**
     * #### `PATCH` `/api/email/verify`
     * Weryfikacja maila
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function verifyEmail(Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
        }

        /** @var EmailVerification $emailVerification */
        $emailVerification = $user->emailVerification()->where('token', $request->token)->first();

        if (Validation::timeComparison($emailVerification->updated_at, env('EMAIL_TOKEN_LIFETIME'), '>')) {
            throw new ApiException(AuthErrorCode::EMAIL_VERIFIFICATION_TOKEN_HAS_EXPIRED());
        }

        $user->markEmailAsVerified();
        $emailVerification->delete();

        $this->checkMissingUserInformation();
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
                __('validation.custom.invalid-provider-id')
            );
        }

        /** @var ExternalAuthentication $externalAuthentication */
        $externalAuthentication = $providerType->externalAuthentication()->where('authentication_id', $encryptedAuthenticationId)->first();

        if (!$externalAuthentication) {

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {

                $encryptedEmail = $encrypter->encrypt($user->getEmail(), 254);

                if (!Validation::checkUserUniqueness('email', $encryptedEmail)) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['email' => [__('validation.unique', ['attribute' => 'email'])]]
                    );
                }

            } else if (strlen($user->getEmail()) > 0 && strlen($user->getEmail()) < 25) {
                
                $encryptedTelephone = $encrypter->encrypt($user->getEmail(), 24);

                if (!Validation::checkUserUniqueness('telephone', $encryptedTelephone)) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['telephone' => [__('validation.unique', ['attribute' => 'numer telefonu'])]]
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

            if (strlen($user->getAvatar())) {
                //TODO Sprawdzić wariant co jest zwracane kiedy użytkownik nie ma ustawionego zdjęcia profilowego
                $avatarFilename = $this->saveAvatar($provider, $user->getAvatar());
            }

            $newUser = [
                'first_name' => $firstName,
                'last_name' => $lastName
            ];

            if (isset($encryptedEmail)) {
                $newUser['email'] = $user->getEmail();
                $newUser['email_verified_at'] = now();
            }

            if (isset($encryptedTelephone)) {
                $newUser['telephone'] = $user->getEmail();
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

        } else {
            Auth::loginUsingId($externalAuthentication->user_id);
            JsonResponse::checkUserAccess();
        }

        $this->checkMissingUserInformation(true);
    }

    /**
     * #### `POST` `/api/user`
     * Uzupełnienie danych użytkownika, bądź też zaktualizowanie istniejących
     * 
     * @param App\Http\Requests\Auth\UpdateUserRequest $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function updateUser(UpdateUserRequest $request, Encrypter $encrypter): void {

        /** @var User $user */
        $user = Auth::user();

        $updateUserInformation = null;

        if ($request->email) {
            $plainEmail = $encrypter->decrypt($request->email);
            $request->merge(['email' => $plainEmail]);
        }

        if ($request->telephone) {
            $plainTelephone = $encrypter->decrypt($request->telephone);
            $request->merge(['telephone' => $plainTelephone]);
        }

        if ($request->facebook_profile) {
            $plainFacebookProfile = $encrypter->decrypt($request->facebook_profile);
            $request->merge(['facebook_profile' => $plainFacebookProfile]);
        }

        $userFirstName = $request->first_name && $request->first_name != $user->first_name ? true : false;
        $userLastName = $request->last_name && $request->last_name != $user->last_name ? true : false;
        $userEmail = $request->email && $request->email != $user->email ? true : false;
        $userBirthDate = $request->birth_date && $request->birth_date != $user->birth_date ? true : false;
        $userAddressCoordinates = $request->address_coordinates && $request->address_coordinates != $user->address_coordinates ? true : false;
        $userTelephone = $request->telephone && $request->telephone != $user->telephone ? true : false;
        $userFacebookProfile = $request->facebook_profile && $request->facebook_profile != $user->facebook_profile ? true : false;
        $userGenderTypeId = $request->gender_type_id && $request->gender_type_id != $user->gender_type_id ? true : false;

        if ($userFirstName || $userLastName) {

            if ($user->last_time_name_changed) {
                if (Validation::timeComparison($user->last_time_name_changed, env('PAUSE_BEFORE_CHANGING_NAME'), '<=')) {
                    throw new ApiException(
                        AuthErrorCode::WAIT_BEFORE_CHANGING_NAME()
                    );
                }
            }
                
            if ($userFirstName) {
                $updateUserInformation['first_name'] = $request->first_name;
            }

            if ($userLastName) {
                $updateUserInformation['last_name'] = $request->last_name;
            }

            $updateUserInformation['last_time_name_changed'] = now();
        }

        if ($userEmail) {
            $updateUserInformation['email'] = $plainEmail;
            $updateUserInformation['email_verified_at'] = null;
        }

        if ($request->password) {
            $updateUserInformation['password'] = $request->password;
            $updateUserInformation['last_time_password_changed'] = now();
        }

        if ($request->avatar) {
            $updateUserInformation['avatar'] = $this->saveAvatar('form', null, $request);

            if ($user->avatar) {
                Storage::delete('avatars/' . $user->avatar);
            }
        }

        if ($userBirthDate) {
            $updateUserInformation['birth_date'] = $request->birth_date;
        }

        if ($userAddressCoordinates) {

            $addressCoordinatesSeparators = explode(';', $request->address_coordinates);

            if (count($addressCoordinatesSeparators) != 2) {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    ['address_coordinates' => [__('validation.regex', ['attribute' => 'addressCoordinates'])]]
                );
            }

            $latitudeLength = strlen($addressCoordinatesSeparators[0]);
            $longitudeLength = strlen($addressCoordinatesSeparators[1]);

            if ($latitudeLength != 7 ||
                $longitudeLength != 7 ||
                $addressCoordinatesSeparators[0][2] != '.' ||
                $addressCoordinatesSeparators[1][2] != '.')
            {
                throw new ApiException(
                    BaseErrorCode::FAILED_VALIDATION(),
                    ['address_coordinates' => [__('validation.regex', ['attribute' => 'addressCoordinates'])]]
                );
            }

            for ($i=0; $i<$latitudeLength; $i++) {

                if (!is_numeric($addressCoordinatesSeparators[0][$i]) && $addressCoordinatesSeparators[0][$i] != '.' ||
                    !is_numeric($addressCoordinatesSeparators[1][$i]) && $addressCoordinatesSeparators[1][$i] != '.')
                {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['address_coordinates' => [__('validation.regex', ['attribute' => 'addressCoordinates'])]]
                    );
                }
            }

            $updateUserInformation['address_coordinates'] = $request->address_coordinates;
        }

        if ($userTelephone) {

            $telephoneLength = strlen($request->telephone);

            for ($i=0; $i<$telephoneLength; $i++) {
                if (!is_numeric($request->telephone[$i])) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['telephone' => [__('validation.regex', ['attribute' => 'numeru telefonu'])]]
                    );
                }
            }

            $updateUserInformation['telephone'] = $request->telephone;
        }

        if ($userFacebookProfile) {
            $updateUserInformation['facebook_profile'] = $request->facebook_profile;
        }

        if ($userGenderTypeId) {
            $updateUserInformation['gender_type_id'] = $request->gender_type_id;
        }

        if ($updateUserInformation) {
            $user->update($updateUserInformation);
        }

        $user->refresh();

        if (isset($updateUserInformation['email'])) {
            $this->sendVerificationEmail(true);
        }

        $this->checkMissingUserInformation();
    }

    /**
     * #### `GET` `/api/user`
     * Pobranie informacji o użytkowniku
     * 
     * @return void
     */
    public function getUser(): void {
        $this->checkMissingUserInformation();
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
     * Zapisanie na serwerze zdjęcia profilowego użytkownika
     * 
     * @param string $provider nazwa serwisu uwierzytelniającego bądź "form" dla opcji wgrywania przez formularz
     * @param string|null $avatarUrl adres URL do zdjecia profilowego z serwisu uwierzytelniającego
     * @param Illuminate\Http\Request $request
     * 
     * @return string
     */
    private function saveAvatar(string $provider, ?string $avatarUrl = null, Request $request = null): string {

        $provider = strtoupper($provider);

        switch ($provider) {

            case 'FACEBOOK':
            case 'GOOGLE':
                $avatarUrlLocation = $avatarUrl;
                // $avatarUrlHeaders = get_headers($avatarUrl, 1);
                // $avatarUrlLocation = isset($avatarUrlHeaders['Location']) ? $avatarUrlHeaders['Location'] : $avatarUrl;
                // $avatarContentType = $avatarUrlHeaders['Content-Type'];

                // if (is_array($avatarContentType)) {
                //     $avatarContentType = $avatarContentType[0];
                // }

                // $avatarFileExtensionSeparators = explode('/', $avatarContentType);
                // $avatarFileExtensionSeparatorsLength = count($avatarFileExtensionSeparators);
                // $avatarFileExtension = '.' . $avatarFileExtensionSeparators[$avatarFileExtensionSeparatorsLength-1];
                break;

            default:
                $avatarUrlLocation = $request->avatar;
                // $avatarFileExtension = '.' . $request->avatar->extension();
                break;
        }

        $avatarFileExtension = '.jpeg';

        $encrypter = new Encrypter;
    
        do {
            $avatarFilename = $encrypter->generatePlainToken(64, $avatarFileExtension);
            $avatarFilenameEncrypted = $encrypter->encryptToken($avatarFilename);
        } while (!Validation::checkUserUniqueness('avatar', $avatarFilenameEncrypted));

        $avatarContents = file_get_contents($avatarUrlLocation);
        $oldImage = imagecreatefromstring($avatarContents);
        $imageWidth = imagesx($oldImage);
        $imageHeight = imagesy($oldImage);
        $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
        imagecopyresampled($newImage , $oldImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageWidth, $imageHeight);
        imagejpeg($newImage, 'storage/avatars/' .$avatarFilename, 100);

        // Storage::put('avatars/' . $avatarFilename, $avatarContents);

        return $avatarFilename;
    }

    /**
     * Sprawdzenie brakujących informacji o użytkowniku i zwrócenie obiektu użytkownika
     * 
     * @param bool $withTokens flaga określająca czy mają zostać utworzone tokeny autoryzacyjne
     * 
     * @return void
     */
    private function checkMissingUserInformation($withTokens = false): void {

        /** @var User $user */
        $user = Auth::user();

        $missingUserInformation = null;

        if (!$user->email) {
            $missingUserInformation['required']['email'] = [__('validation.custom.is-missing', ['attribute' => 'adres email'])];
        }

        if (!$user->birth_date) {
            $missingUserInformation['required']['birth_date'] = [__('validation.custom.is-missing', ['attribute' => 'datę urodzenia'])];
        }

        if (!$user->avatar) {
            $missingUserInformation['optional']['avatar'] = [__('validation.custom.is-missing', ['attribute' => 'zdjęcie profilowe'])];
        }

        if (!$user->address_coordinates) {
            $missingUserInformation['optional']['address_coordinates'] = [__('validation.custom.is-missing', ['attribute' => 'adres zamieszkania'])];
        }

        if (!$user->telephone) {
            $missingUserInformation['optional']['telephone'] = [__('validation.custom.is-missing', ['attribute' => 'numer telefonu'])];
        }

        if (!$user->facebook_profile) {
            $missingUserInformation['optional']['facebook_profile'] = [__('validation.custom.is-missing', ['attribute' => 'adres profilu na Facebooku'])];
        }

        if (!$user->gender_type_id) {
            $missingUserInformation['optional']['gender_type_id'] = [__('validation.custom.is-missing', ['attribute' => 'płeć'])];
        }

        if ($withTokens) {
            JsonResponse::prepareCookies();
        }

        if (isset($missingUserInformation['required']) || !$user->email_verified_at) {
            throw new ApiException(
                $user->email_verified_at ? AuthErrorCode::MISSING_USER_INFORMATION() : AuthErrorCode::UNVERIFIED_EMAIL(),
                ['user' => $user],
                ['missing_user_information' => $missingUserInformation]
            );
        }

        JsonResponse::sendSuccess(
            ['user' => $user],
            ['missing_user_information' => $missingUserInformation]
        );
    }
}
