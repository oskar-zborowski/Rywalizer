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
use App\Mail\EmailVerification as MailEmailVerification;
use App\Models\AccountActionType;
use App\Models\AccountOperation;
use App\Models\ExternalAuthentication;
use App\Models\GenderType;
use App\Models\PersonalAccessToken;
use App\Models\ProviderType;
use App\Models\RoleType;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        /** @var User $user */
        $user = Auth::user();
        $user->checkMissingUserInformation(true);
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
        $newUser = User::create($request->only('first_name', 'last_name', 'email', 'password', 'birth_date', 'gender_type_id'));

        Auth::loginUsingId($newUser->id);

        JsonResponse::checkUserAccess($request, 'REGISTER');

        /** @var User $user */
        $user = Auth::user();
        $user->sendVerificationEmail(true);
        $user->checkMissingUserInformation(true);
    }

    /**
     * #### `POST` `/api/auth/forgot-password`
     * Proces utworzenia niezbędnych danych do przeprowadzenia resetu hasła
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

        $accountOperationTypeId = Validation::getAccountOperationTypeId('PASSWORD_RESET');

        /** @var AccountOperation $accountOperation */
        $accountOperation = AccountOperation::where([
            'account_operation_type_id' => $accountOperationTypeId,
            'token' => $request->token
        ])->first();

        if (!$accountOperation) {
            throw new ApiException(AuthErrorCode::INVALID_PASSWORD_RESET_TOKEN());
        }

        $accountOperation->user()->first()->resetPassword($request, $accountOperation);
    }

    /**
     * #### `POST` `/api/user/email/verification-notification`
     * Proces utworzenia niezbędnych danych do zweryfikowania maila
     * 
     * @return void
     */
    public function sendVerificationEmail(): void {
        /** @var User $user */
        $user = Auth::user();
        $user->sendVerificationEmail();
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
        $user->checkMissingUserInformation();
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
        $externalAuthentication = $providerType->externalAuthentication()->where('external_authentication_id', $encryptedAuthenticationId)->first();

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
                    $accountOperationTypeId = Validation::getAccountOperationTypeId('EMAIL_VERIFICATION');
                    $foundUser->accountOperation()->where('account_operation_type_id', $accountOperationTypeId)->delete();
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

            /** @var User $createdUser */
            $createdUser = User::updateOrCreate([], $newUser);

            $createdUser->externalAuthentication()->create([
                'exteranal_authentication_id' => $authenticationId,
                'provider_type_id' => $providerType->id
            ]);

            Auth::loginUsingId($createdUser->id);

            $activity = 'REGISTER_' . strtoupper($provider);
            JsonResponse::checkUserAccess(null, $activity);

            if ($createdUser->email) {
                if (!$foundUser) {
                    Mail::to($createdUser)->send(new MailEmailVerification());
                } else {
                    // TODO Jakiś inny mail, że dodano możliwość logowania się providerem
                }
            }

        } else {
            Auth::loginUsingId($externalAuthentication->user_id);
            $activity = 'LOGIN_' . strtoupper($provider);
            JsonResponse::checkUserAccess(null, $activity);
        }

        /** @var User $user */
        $user = Auth::user();
        $user->checkMissingUserInformation(true);
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
        $isUpdatedEmail = $user->updateInformation($request);

        if ($isUpdatedEmail) {
            $user->sendVerificationEmail(true);
        }

        $user->checkMissingUserInformation();
    }

    /**
     * #### `GET` `/api/user`
     * Pobranie prywatnych informacji o użytkowniku
     * 
     * @return void
     */
    public function getUser(): void {
        /** @var User $user */
        $user = Auth::user();
        $user->checkMissingUserInformation();
    }

    /**
     * #### `GET` `/api/users`
     * Pobranie szczegółowych informacji o użytkownikach
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function getUsers(Request $request): void {

        $perPage = $this->getNumberOfItemsPerPage($request);

        /** @var User $users */
        $users = User::filter()->paginate($perPage);

        $result = $this->preparePagination($users, 'detailedInformation');

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
    }

    /**
     * #### `GET` `/api/user/{id}/authentication`
     * Pobranie informacji o uwierzytelnieniach użytkownika
     * 
     * @param int $id identyfikator użytkownika
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function getUserAuthentication(int $id, Request $request): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->id != $id) {

            if ($user->roleType()->first()->name != 'ADMIN') {
                throw new ApiException(BaseErrorCode::PERMISSION_DENIED());
            }

            if (!$user->hasVerifiedEmail()) {
                throw new ApiException(
                    BaseErrorCode::PERMISSION_DENIED(),
                    'Your email address is not verified.'
                );
            }
        }

        $perPage = $this->getNumberOfItemsPerPage($request);

        if ($user->roleType()->first()->name == 'ADMIN' && $user->hasVerifiedEmail()) {

            /** @var User $user */
            $user = User::where('id', $id)->first();

            if ($user) {
                /** @var Authentication $authentications */
                $authentications = $user->authentication()->filter()->paginate($perPage);
            } else {
                $authentications = null;
            }

            $result = $this->preparePagination($authentications, 'detailedInformation');

        } else {

            /** @var Authentication $authentications */
            $authentications = $user->authentication()->filter()->paginate($perPage);

            $result = $this->preparePagination($authentications, 'privateInformation');
        }

        JsonResponse::sendSuccess($result['data'], $result['metadata']);
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

        $user->checkMissingUserInformation();
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

        $user->checkMissingUserInformation();
    }

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
}
