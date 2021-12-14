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
 * Klasa odpowiedzialna za wszelkie kwestie związane z uwierzytelnianiem i jego pochodnymi, a także instancją użytkownika
 */
class AuthController extends Controller
{
    /**
     * #### `POST` `/api/auth/login`
     * Proces logowania użytkownika
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function login(Request $request): void {

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw new ApiException(AuthErrorCode::INVALID_CREDENTIALS());
        }
    
        JsonResponse::checkUserAccess($request, 'LOGIN');

        $this->checkMissingUserInformation(true);
    }

    /**
     * #### `POST` `/api/auth/register`
     * Proces rejestracji nowego użytkownika
     * 
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function register(RegisterRequest $request, Encrypter $encrypter): void {

        $email = $encrypter->decrypt($request->email);
        $request->merge(['email' => $email]);

        /** @var User $user */
        $user = User::create($request->only('first_name', 'last_name', 'email', 'password', 'birth_date', 'gender_type_id'));
    
        Auth::loginUsingId($user->id);

        JsonResponse::checkUserAccess($request, 'REGISTER');

        $this->sendVerificationEmail(true);
        $this->checkMissingUserInformation(true);
    }

    /**
     * #### `POST` `/api/auth/forgot-password`
     * Wysłanie maila z linkiem do resetu hasła
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
            }
        }

        do {
            $token = $encrypter->generateToken(64);
            $encryptedToken = $encrypter->encrypt($token);
        } while (!empty(PasswordReset::where('token', $encryptedToken)->first()));

        $user->passwordReset()->updateOrCreate([],
        [
            'token' => $token,
            'email_sending_counter' => $emailSendingCounter
        ]);

        $url = env('APP_URL') . '/reset-password?token=' . $token; // TODO Poprawić na prawidłowy URL
        Mail::to($user)->send(new MailPasswordReset($url));

        JsonResponse::sendSuccess();
    }

    /**
     * #### `PATCH` `/api/auth/reset-password`
     * Proces resetu hasła
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

        if (!$request->do_not_logout) {
            PersonalAccessToken::where('tokenable_id', $passwordReset->user_id)->delete();
        }

        $passwordReset->delete();

        JsonResponse::sendSuccess();
    }

    /**
     * #### `POST` `/api/email/verification-notification`
     * Wysłanie maila z linkiem aktywacyjnym
     * 
     * @param bool $afterRegistartion flaga z informacją czy wywołanie metody jest pochodną procesu rejestracji nowego użytkownika
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
                    }
                }
            }

            $encrypter = new Encrypter;

            do {
                $token = $encrypter->generateToken(64);
                $encryptedToken = $encrypter->encrypt($token);
            } while (!empty(EmailVerification::where('token', $encryptedToken)->first()));

            $user->emailVerification()->updateOrCreate([],
            [
                'token' => $token,
                'email_sending_counter' => $emailSendingCounter
            ]);

            $url = env('APP_URL') . '/email/verify?token=' . $token; // TODO Poprawić na prawidłowy URL
            Mail::to($user)->send(new VerificationEmail($url, $afterRegistartion));

            if (!$afterRegistartion) {
                JsonResponse::sendSuccess();
            }
        }
    }

    /**
     * #### `PATCH` `/api/email/verify`
     * Proces weryfikacji maila
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

        if (!$emailVerification) {
            throw new ApiException(AuthErrorCode::INVALID_EMAIL_VERIFIFICATION_TOKEN());
        }

        if (Validation::timeComparison($emailVerification->updated_at, env('EMAIL_TOKEN_LIFETIME'), '>')) {
            throw new ApiException(AuthErrorCode::EMAIL_VERIFIFICATION_TOKEN_HAS_EXPIRED());
        }

        $user->timestamps = false;
        $user->markEmailAsVerified();
        $emailVerification->delete();

        $this->checkMissingUserInformation();
    }

    /**
     * #### `DELETE` `/api/auth/logout`
     * Proces wylogowania użytkownika
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
     * #### `DELETE` `/api/auth/logout-other-devices`
     * Proces wylogowania użytkownika ze wszystkich urządzeń poza obecnym
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function logoutOtherDevices(Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        $user->tokens()->delete();

        JsonResponse::checkDevice($request, 'REFRESH_TOKEN');
        JsonResponse::prepareCookies();
        JsonResponse::sendSuccess();
    }

    /**
     * #### `GET` `/api/auth/{provider}/redirect`
     * Przekierowanie użytkownika do zewnętrznego serwisu uwierzytelniającego (FACEBOOK, GOOGLE)
     *
     * @param string $provider nazwa zewnętrznego serwisu uwierzytelniającego
     * 
     * @return Illuminate\Http\RedirectResponse
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
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
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

        $authenticationId = (strlen($user->getId()) > 0 && strlen($user->getId()) < 256) ? $user->getId() : null;
        $encryptedAuthenticationId = $encrypter->encrypt($authenticationId, 255);

        if (!$authenticationId) {
            throw new ApiException(
                AuthErrorCode::INVALID_CREDENTIALS_PROVIDED(),
                __('validation.custom.invalid-provider-id')
            );
        }

        /** @var ExternalAuthentication $externalAuthentication */
        $externalAuthentication = $providerType->externalAuthentication()->where('authentication_id', $encryptedAuthenticationId)->first();
        
        if (!$externalAuthentication) {

            $foundUser = null;

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {

                $encryptedEmail = $encrypter->encrypt($user->getEmail(), 254);

                /** @var User $foundUser */
                $foundUser = User::where('email', $encryptedEmail)->first();

            } else if (strlen($user->getEmail()) > 0 && strlen($user->getEmail()) < 25) {
                
                $encryptedTelephone = $encrypter->encrypt($user->getEmail(), 24);

                /** @var User $foundUser */
                $foundUser = User::where('telephone', $encryptedTelephone)->first();
            }

            $newUser = null;

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
                    $foundUser->emailVerification()->delete();
                }

                $newUser['password'] = null;
            }

            if (isset($encryptedEmail)) {
                $newUser['email_verified_at'] = now();
            }

            if (strlen($user->getAvatar()) && (!$foundUser || !$foundUser->avatar)) {
                // TODO Sprawdzić wariant co jest zwracane kiedy użytkownik nie ma ustawionego zdjęcia profilowego
                $newUser['avatar'] = $this->saveAvatar($user->getAvatar());
            }

            /** @var User $createUser */
            $createUser = User::updateOrCreate([], $newUser);

            $createUser->externalAuthentication()->create([
                'authentication_id' => $authenticationId,
                'provider_type_id' => $providerType->id
            ]);

            Auth::loginUsingId($createUser->id);
            JsonResponse::checkUserAccess(null, 'REGISTER_' . strtoupper($provider));

            if ($createUser->email) {
                if (!$foundUser) {
                    Mail::to($createUser)->send(new VerificationEmail());
                } else {
                    // TODO Jakiś inny mail, że dodano możliwość logowania się providerem
                }
            }

        } else {
            Auth::loginUsingId($externalAuthentication->user_id);
            JsonResponse::checkUserAccess(null, 'LOGIN_' . strtoupper($provider));
        }

        $this->checkMissingUserInformation(true);
    }

    /**
     * #### `PATCH` `/api/user`
     * Proces uzupełnienia danych użytkownika, bądź też zaktualizowania już istniejących
     * 
     * @param App\Http\Requests\Auth\UpdateUserRequest $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function updateUser(UpdateUserRequest $request, Encrypter $encrypter): void {

        if ($request->email) {
            $email = $encrypter->decrypt($request->email);
            $request->merge(['email' => $email]);
        }

        if ($request->telephone) {
            $telephone = $encrypter->decrypt($request->telephone);
            $request->merge(['telephone' => $telephone]);
        }

        if ($request->facebook_profile) {
            $facebookProfile = $encrypter->decrypt($request->facebook_profile);
            $request->merge(['facebook_profile' => $facebookProfile]);
        }

        if ($request->instagram_profile) {
            $instagramProfile = $encrypter->decrypt($request->instagram_profile);
            $request->merge(['instagram_profile' => $instagramProfile]);
        }

        /** @var User $user */
        $user = Auth::user();

        $updateUserInformation = null;

        $userFirstName = $request->first_name && $request->first_name != $user->first_name;
        $userLastName = $request->last_name && $request->last_name != $user->last_name;
        $userEmail = $request->email && $request->email != $user->email;
        $userBirthDate = $request->birth_date && $request->birth_date != $user->birth_date;

        if ($userFirstName || $userLastName) {

            if ($user->last_time_name_changed && 
                Validation::timeComparison($user->last_time_name_changed, env('PAUSE_BEFORE_CHANGING_NAME'), '<='))
            {
                throw new ApiException(
                    AuthErrorCode::WAIT_BEFORE_CHANGING_NAME()
                );
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
            $updateUserInformation['email'] = $request->email;
            $updateUserInformation['email_verified_at'] = null;
        }

        if ($request->password) {
            $updateUserInformation['password'] = $request->password;
            $updateUserInformation['last_time_password_changed'] = now();
        }

        if ($userBirthDate) {
            $updateUserInformation['birth_date'] = $request->birth_date;
        }

        if ($request->address_coordinates != $user->address_coordinates) {

            if ($request->address_coordinates) {

                $userAddressCoordinatesSeparators = explode(';', $request->address_coordinates);

                if (count($userAddressCoordinatesSeparators) != 2) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['address_coordinates' => [__('validation.regex')]]
                    );
                }

                $latitudeLength = strlen($userAddressCoordinatesSeparators[0]);
                $longitudeLength = strlen($userAddressCoordinatesSeparators[1]);

                if ($latitudeLength != 7 ||
                    $longitudeLength != 7 ||
                    $userAddressCoordinatesSeparators[0][2] != '.' ||
                    $userAddressCoordinatesSeparators[1][2] != '.')
                {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
                        ['address_coordinates' => [__('validation.regex')]]
                    );
                }

                for ($i=0; $i<$latitudeLength; $i++) {
                    if ((!is_numeric($userAddressCoordinatesSeparators[0][$i]) ||
                        !is_numeric($userAddressCoordinatesSeparators[1][$i])) &&
                        $i != 2)
                    {
                        throw new ApiException(
                            BaseErrorCode::FAILED_VALIDATION(),
                            ['address_coordinates' => [__('validation.regex')]]
                        );
                    }
                }
            }

            $updateUserInformation['address_coordinates'] = $request->address_coordinates;
        }

        if ($request->telephone != $user->telephone) {

            if ($request->telephone) {

                $telephoneLength = strlen($request->telephone);

                for ($i=0; $i<$telephoneLength; $i++) {
                    if (!is_numeric($request->telephone[$i])) {
                        throw new ApiException(
                            BaseErrorCode::FAILED_VALIDATION(),
                            ['telephone' => [__('validation.regex')]]
                        );
                    }
                }
            }

            $updateUserInformation['telephone'] = $request->telephone;
        }

        if ($request->facebook_profile != $user->facebook_profile) {
            $updateUserInformation['facebook_profile'] = $request->facebook_profile;
        }

        if ($request->instagram_profile != $user->instagram_profile) {
            $updateUserInformation['instagram_profile'] = $request->instagram_profile;
        }

        if ($request->gender_type_id != $user->gender_type_id) {
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
     * #### `POST` `/api/user/avatar/upload`
     * Wgranie zdjęcia profilowego
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function uploadAvatar(Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if ($request->avatar) {

            $updateUserInformation['avatar'] = $this->saveAvatar($request->avatar);

            if ($user->avatar) {
                $oldAvatarPath = 'avatars/' . $user->avatar;
                Storage::delete($oldAvatarPath);
            }

            $user->update($updateUserInformation);
        }

        $this->checkMissingUserInformation();
    }

    /**
     * #### `DELETE` `/api/user/avatar/delete`
     * Usunięcie zdjęcia profilowego
     * 
     * @return void
     */
    public function deleteAvatar(): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->avatar) {
            $avatarPath = 'avatars/' . $user->avatar;
            Storage::delete($avatarPath);

            $user->update(['avatar' => null]);
        }

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

    /**
     * Zapisanie na serwerze zdjęcia profilowego użytkownika
     * 
     * @param string $avatarPath ścieżka do zdjęcia profilowego
     * 
     * @return string
     */
    private function saveAvatar(string $avatarPath): string {

        $avatarFileExtension = '.' . env('AVATAR_FILE_EXTENSION');

        $encrypter = new Encrypter;
    
        do {
            $avatarFilename = $encrypter->generateToken(64, $avatarFileExtension);
            $encryptedAvatarFilename = $encrypter->encrypt($avatarFilename);
        } while (!Validation::checkUserUniqueness('avatar', $encryptedAvatarFilename));

        $avatarDestination = 'storage/avatars/' . $avatarFilename;
        $avatarContents = file_get_contents($avatarPath);
        $oldImage = imagecreatefromstring($avatarContents);
        $imageWidth = imagesx($oldImage);
        $imageHeight = imagesy($oldImage);
        $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
        imagecopyresampled($newImage , $oldImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageWidth, $imageHeight);
        imagejpeg($newImage, $avatarDestination, 100); // TODO Potestować ile maksymalnie można zmniejszyć jakość obrazu, żeby nadal był akceptowalny

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

        if (!$user->instagram_profile) {
            $missingUserInformation['optional']['instagram_profile'] = [__('validation.custom.is-missing', ['attribute' => 'adres profilu na Instagramie'])];
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
                ['user' => $user->privateData()],
                ['missing_user_information' => $missingUserInformation]
            );
        }

        JsonResponse::sendSuccess(
            ['user' => $user->privateData()],
            ['missing_user_information' => $missingUserInformation]
        );
    }
}
