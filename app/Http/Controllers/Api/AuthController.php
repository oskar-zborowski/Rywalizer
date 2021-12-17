<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ApiException;
use App\Http\Controllers\Controller;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateUserRequest;
use App\Http\Responses\JsonResponse;
use App\Mail\VerificationEmail;
use App\Models\AccountActionType;
use App\Models\ExternalAuthentication;
use App\Models\GenderType;
use App\Models\PasswordReset;
use App\Models\PersonalAccessToken;
use App\Models\ProviderType;
use App\Models\RoleType;
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
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function resetPassword(Request $request): void {
        /** @var PasswordReset $passwordReset */
        $passwordReset = PasswordReset::where('token', $request->token)->first();
        $passwordReset->resetPassword($request);
    }

    /**
     * #### `POST` `/api/user/email/verification-notification`
     * Wysłanie maila z linkiem aktywacyjnym
     * 
     * @param bool $afterRegistartion flaga z informacją czy wywołanie metody jest pochodną procesu rejestracji nowego użytkownika
     * 
     * @return void
     */
    public function sendVerificationEmail(bool $afterRegistartion = false): void {
        /** @var User $user */
        $user = Auth::user();
        $user->sendVerificationEmail($afterRegistartion);
    }

    /**
     * #### `PATCH` `/api/user/email/verify`
     * Proces weryfikacji maila
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function verifyEmail(Request $request): void {

        /** @var User $user */
        $user = Auth::user();
        $user->verifyEmail($request);

        $this->checkMissingUserInformation();
    }

    /**
     * #### `DELETE` `/api/auth/logout/me`
     * Proces wylogowania użytkownika
     * 
     * @param Illuminate\Http\Request $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function logoutMe(Request $request, Encrypter $encrypter): void {

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
     * #### `DELETE` `/api/auth/logout/other-devices`
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
                    $foundUser->emailVerification()->delete();
                }

                $newUser['password'] = null;
            }

            if (isset($encryptedEmail)) {
                $newUser['email_verified_at'] = now();
            }

            if (strlen($user->getAvatar()) > 0 && (!$foundUser || !$foundUser->avatar)) {
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
     * 
     * @return void
     */
    public function updateUser(UpdateUserRequest $request): void {

        /** @var User $user */
        $user = Auth::user();
        $updatedEmail = $user->updateInformation($request);

        if ($updatedEmail) {
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

            $updateInformation['avatar'] = $this->saveAvatar($request->avatar);

            if ($user->avatar) {
                $oldAvatarPath = 'avatars/' . $user->avatar;
                Storage::delete($oldAvatarPath);
            }

            $user->update($updateInformation);
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
     * #### `GET` `/api/provider/types`
     * Pobranie listy zewnętrznych serwisów uwierzytelniających
     * 
     * @return void
     */
    public function getProviderTypes(): void {
        $providerTypes = ProviderType::get();
        JsonResponse::sendSuccess(['providerTypes' => $providerTypes]);
    }

    /**
     * #### `GET` `/api/gender/types`
     * Pobranie listy płci
     * 
     * @return void
     */
    public function getGenderTypes(): void {
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
        $accountActionTypes = AccountActionType::get();
        JsonResponse::sendSuccess(['accountActionTypes' => $accountActionTypes]);
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

        $encrypter = new Encrypter;
        $avatarFileExtension = '.' . env('AVATAR_FILE_EXTENSION');
        $avatarFilename = $encrypter->generateToken(64, User::class, 'avatar', $avatarFileExtension);

        $avatarContents = file_get_contents($avatarPath);
        $uploadedImage = imagecreatefromstring($avatarContents);
        $imageWidth = imagesx($uploadedImage);
        $imageHeight = imagesy($uploadedImage);
        $newImage = imagecreatetruecolor($imageWidth, $imageHeight);
        imagecopyresampled($newImage , $uploadedImage, 0, 0, 0, 0, $imageWidth, $imageHeight, $imageWidth, $imageHeight);

        $avatarDestination = 'storage/avatars/' . $avatarFilename;
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
        $user->checkMissingUserInformation($withTokens);
    }
}
