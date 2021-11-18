<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Http\JsonResponse;
use App\Http\Requests\Auth\FillMissingUserInfoRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\AuthResponse;
use App\Http\Responses\DefaultResponse;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
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
            JsonResponse::sendError(
                AuthResponse::INVALID_CREDENTIALS,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $user = Auth::user();

        $emailVerifiedAt = $user->email_verified_at;
        $accountDeletedAt = $user->account_deleted_at;
        $accountBlockedAt = $user->account_blocked_at;

        if ($accountBlockedAt) {
            JsonResponse::sendError(
                AuthResponse::ACOUNT_BLOCKED,
                Response::HTTP_UNAUTHORIZED
            );
        }

        if ($accountDeletedAt) {
            JsonResponse::sendError(
                AuthResponse::ACOUNT_DELETED,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $this->prepareCookies();

        if (!$emailVerifiedAt) {
            JsonResponse::sendError(
                AuthResponse::UNVERIFIED_EMAIL,
                Response::HTTP_FORBIDDEN,
                [$user]
            );
        }

        JsonResponse::sendSuccess([$user]);
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

        $encryptedEmail = $request->email;
        $plainPassword = $request->password;

        $request->merge(['email' => $encrypter->decrypt($encryptedEmail)]);
        $request->merge(['password' => $encrypter->hash($plainPassword)]);

        User::create($request->only('first_name', 'last_name', 'email', 'password', 'gender_type_id', 'birth_date'));

        Auth::attempt([
            'email' => $encryptedEmail,
            'password' => $plainPassword
        ]);

        $user = Auth::user();

        $this->prepareCookies();
        $this->sendVerificationEmail(true);

        JsonResponse::sendError(
            AuthResponse::UNVERIFIED_EMAIL,
            Response::HTTP_FORBIDDEN,
            [$user]
        );
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

        $emailSendingCounter = 0;

        $passwordResetToken = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if ($passwordResetToken) {

            $emailSendingCounter = $passwordResetToken->email_sending_counter;

            $waitingDate = date('Y-m-d H:i:s', strtotime('+' . env('PAUSE_BEFORE_RETRYING')*60 . ' seconds', strtotime($passwordResetToken->created_at)));
            $now = date('Y-m-d H:i:s');

            if ($now <= $waitingDate) {
                JsonResponse::sendError(
                    AuthResponse::WAIT_BEFORE_RETRYING,
                    Response::HTTP_NOT_ACCEPTABLE
                );
            } else {
                DB::table('password_resets')->where('id', $passwordResetToken->id)->delete();
            }
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {

            $email = $encrypter->decrypt($request->email);

            DB::table('password_resets')
                ->where('email', $email)
                ->update([
                    'email' => $request->email,
                    'email_sending_counter' => $emailSendingCounter+1
                ]);

            JsonResponse::sendSuccess();
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    /**
     * #### `PUT` `/api/reset-password`
     * Reset hasła
     * 
     * @param Illuminate\Http\Request $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * @param Illuminate\Contracts\Hashing\Hasher $hasher
     * 
     * @return void
     */
    public function resetPassword(Request $request, Encrypter $encrypter, Hasher $hasher): void {

        $resetTokens = DB::table('password_resets')->get();

        foreach ($resetTokens as $rT) {
            if ($hasher->check($request->token, $rT->token)) {
                $email = $encrypter->decrypt($rT->email);

                DB::table('password_resets')
                    ->where('id', $rT->id)
                    ->update(['email' => $email]);
                
                break;
            }
        }

        $status = Password::reset(
            $request->only('password', 'password_confirmation', 'token'),
            function ($user) use ($request) {

                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();

                if (!$request->do_not_logout) {
                    $user->tokens()->delete();
                }

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            JsonResponse::sendSuccess();
        }

        JsonResponse::sendError(
            AuthResponse::INVALID_PASSWORD_RESET_TOKEN,
            Response::HTTP_BAD_REQUEST
        );
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

        if (!$afterRegistartion) {

            if ($user->hasVerifiedEmail()) {
                JsonResponse::sendError(
                    AuthResponse::EMAIL_ALREADY_VERIFIED,
                    Response::HTTP_NOT_ACCEPTABLE
                );
            }

            $waitingDate = date('Y-m-d H:i:s', strtotime('+' . env('PAUSE_BEFORE_RETRYING')*60 . ' seconds', strtotime($user->updated_at)));
            $now = date('Y-m-d H:i:s');
    
            if ($now <= $waitingDate && $user->verification_email_counter > 1) {
                JsonResponse::sendError(
                    AuthResponse::WAIT_BEFORE_RETRYING,
                    Response::HTTP_NOT_ACCEPTABLE
                );
            }

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'verification_email_counter' => $user->verification_email_counter+1,
                    'updated_at' => $now
                ]);

            $user->sendEmailVerificationNotification();
    
            JsonResponse::sendSuccess();

        } else {
            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'verification_email_counter' => 1,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);

            $user->sendEmailVerificationNotification();
        }
    }

    /**
     * #### `PUT` `/api/verify-email/{id}/{hash}`
     * Weryfikacja maila
     * 
     * @return void
     */
    public function verifyEmail(): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            JsonResponse::sendError(
                AuthResponse::EMAIL_ALREADY_VERIFIED,
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        JsonResponse::sendSuccess();
    }

    /**
     * #### `DELETE` `/api/logout`
     * Wylogowanie użytkownika
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function logout(Request $request): void {

        $request->user()->currentAccessToken()->delete();

        JsonResponse::deleteCookie('JWT');
        JsonResponse::deleteCookie('REFRESH-TOKEN');
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

        $this->prepareCookies();

        JsonResponse::sendSuccess();
    }

    /**
     * #### `POST` `/api/refresh-token`
     * Odświeżenie tokenu autoryzacyjnego
     * 
     * @param Illuminate\Http\Request $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function refreshToken(Request $request, Encrypter $encrypter): void {

        $plainRefreshToken = $request->cookie('REFRESH-TOKEN');
        $refreshToken = $encrypter->encryptToken($plainRefreshToken);
        $personalAccessToken = DB::table('personal_access_tokens')->where('refresh_token', $refreshToken)->first();

        if (!$personalAccessToken) {
            JsonResponse::deleteCookie('REFRESH-TOKEN');

            JsonResponse::sendError(
                AuthResponse::INVALID_REFRESH_TOKEN,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $expirationDate = date('Y-m-d H:i:s', strtotime('+' . env('REFRESH_TOKEN_LIFETIME') . ' minutes', strtotime($personalAccessToken->created_at)));
        $now = date('Y-m-d H:i:s');

        if ($now > $expirationDate) {
            JsonResponse::deleteCookie('REFRESH-TOKEN');

            JsonResponse::sendError(
                AuthResponse::REFRESH_TOKEN_HAS_EXPIRED,
                Response::HTTP_UNAUTHORIZED
            );
        }

        $userId = $personalAccessToken->tokenable_id;
        $personalAccessTokenId = $personalAccessToken->id;

        DB::table('personal_access_tokens')->where('id', $personalAccessTokenId)->delete();

        $user = Auth::loginUsingId($userId);

        $this->prepareCookies();

        JsonResponse::sendSuccess([$user]);
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
        return Socialite::driver($provider)->stateless()->redirect();
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

        $providerId = $this->validateProvider($provider);

        $user = Socialite::driver($provider)->stateless()->user();

        $authenticationId = $user->getId() !== null ? (string)($user->getId()) : null;

        if (!$authenticationId || strlen($authenticationId) < 1 || strlen($authenticationId) > 254) {
            JsonResponse::sendError(
                DefaultResponse::FAILED_VALIDATION,
                Response::HTTP_BAD_REQUEST,
                ['authentication_id' => ['The provider returned an invalid id.']] // TODO Zmienić kiedy pojawią się langi
            );
        }

        $externalAuthentication = DB::table('external_authentications')
            ->where('authentication_id', $encrypter->encrypt($authenticationId, 254))
            ->where('provider_type_id', $providerId)
            ->first();

        if (!$externalAuthentication) {

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $externalAuthentication = DB::table('users')
                    ->where('email', $encrypter->encrypt($user->getEmail(), 254))
                    ->first();

                if ($externalAuthentication) {
                    JsonResponse::sendError(
                        DefaultResponse::FAILED_VALIDATION,
                        Response::HTTP_BAD_REQUEST,
                        ['email' => ['The email has already been taken.']] // TODO Zmienić kiedy pojawią się langi
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

            if ($user->getAvatar() !== null) {
                $avatarFilename = $this->saveAvatar($provider, $user->getAvatar());
            }

            $newUser = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'avatar' => isset($avatarFilename) ? $avatarFilename : null,
            ];

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $newUser['email'] = $user->getEmail();
                $newUser['email_verified_at'] = now();
            }

            $createUser = User::create($newUser);

            $createUser->externalAuthentication()->create([
                'authentication_id' => $encrypter->encrypt($authenticationId, 254),
                'provider_type_id' => $providerId
            ]);

            Auth::loginUsingId($createUser->id);
        } else {
            Auth::loginUsingId($externalAuthentication->user_id);
        }

        $user = Auth::user();

        if ($user->account_blocked_at) {
            JsonResponse::sendError(
                AuthResponse::ACOUNT_BLOCKED,
                Response::HTTP_UNAUTHORIZED,
            );
        }

        if ($user->account_deleted_at) {
            JsonResponse::sendError(
                AuthResponse::ACOUNT_DELETED,
                Response::HTTP_UNAUTHORIZED,
            );
        }

        $this->prepareCookies();

        $missingInfo = null;

        if (!$user->email) {
            $missingInfo['email'] = ['The email field is missing.']; // TODO Zmienić kiedy pojawią się langi
        }

        if (!$user->birth_date) {
            $missingInfo['birthDate'] = ['The birth date field is missing.']; // TODO Zmienić kiedy pojawią się langi
        }

        if (!$user->gender_type_id) {
            $missingInfo['genderTypeId'] = ['The gender type id field is missing. (NOT REQUIRED)']; // TODO Zmienić kiedy pojawią się langi
        }

        if (!$user->avatar) {
            $missingInfo['avatar'] = ['The avatar field is missing. (NOT REQUIRED)']; // TODO Zmienić kiedy pojawią się langi
        }

        if (isset($missingInfo['email']) || isset($missingInfo['birthDate'])) {
            JsonResponse::sendError(
                AuthResponse::MISSING_USER_INFORMATION,
                Response::HTTP_FORBIDDEN,
                [$missingInfo]
            );
        }

        if (!$user->email_verified_at) {
            JsonResponse::sendError(
                AuthResponse::UNVERIFIED_EMAIL,
                Response::HTTP_FORBIDDEN,
                [$missingInfo ? $missingInfo : $user]
            );
        }

        if ($missingInfo) {
            JsonResponse::sendSuccess([$missingInfo]);
        }

        JsonResponse::sendSuccess([$user]);
    }

    /**
     * #### `GET` `/api/user`
     * Pobranie informacji o użytkowniku
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function user(Request $request): void {
        JsonResponse::sendSuccess([$request->user()]);
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

        if ($user->email_verified_at && $user->avatar && $user->gender_type_id && $user->birth_date) {
            JsonResponse::sendError(
                AuthResponse::ALL_USER_FIELDS_ARE_COMPLETE,
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        $plainEmail = $request->email;

        if (!$user->email && $plainEmail) {
            $request->merge(['email' => $encrypter->encrypt($plainEmail, 254)]);

            $request->validate([
                'email' => 'unique:users'
            ]);
        }

        $missingInfo = null;
        $supplementaryInfo = null;

        if (!$user->email) {
            if (!$plainEmail) {
                $missingInfo['email'] = ['The email field is missing.']; // TODO Zmienić kiedy pojawią się langi
            } else {
                $supplementaryInfo['email'] = $request->email;
            }
        }

        if (!$user->birth_date) {
            if (!$request->birth_date) {
                $missingInfo['birthDate'] = ['The birth date field is missing.']; // TODO Zmienić kiedy pojawią się langi
            } else {
                $supplementaryInfo['birth_date'] = $encrypter->encrypt($request->birth_date, 10);
            }
        }

        if (!$user->gender_type_id) {
            if (!$request->gender_type_id) {
                $missingInfo['genderTypeId'] = ['The gender type id field is missing. (NOT REQUIRED)']; // TODO Zmienić kiedy pojawią się langi
            } else {
                $supplementaryInfo['gender_type_id'] = $request->gender_type_id;
            }
        }

        if (!$user->avatar) {
            if (!$request->avatar) {
                $missingInfo['avatar'] = ['The avatar field is missing. (NOT REQUIRED)']; // TODO Zmienić kiedy pojawią się langi
            } else {
                $supplementaryInfo['avatar'] = $encrypter->encrypt($request->avatar, 24);
            }
        }

        if ($supplementaryInfo) {
            $supplementaryInfo['updated_at'] = date('Y-m-d H:i:s');
            DB::table('users')
                ->where('id', $user->id)
                ->update($supplementaryInfo);
        }

        $user->refresh();

        if (isset($supplementaryInfo['email'])) {
            $this->sendVerificationEmail(true);
        }

        if (isset($missingInfo['email']) || isset($missingInfo['birthDate'])) {
            JsonResponse::sendError(
                AuthResponse::MISSING_USER_INFORMATION,
                Response::HTTP_FORBIDDEN,
                [$missingInfo]
            );
        }

        if (!$user->email_verified_at) {
            JsonResponse::sendError(
                AuthResponse::UNVERIFIED_EMAIL,
                Response::HTTP_FORBIDDEN,
                [$missingInfo ? $missingInfo : $user]
            );
        }

        if ($missingInfo) {
            JsonResponse::sendSuccess([$missingInfo]);
        }

        JsonResponse::sendSuccess([$user]);
    }

    /**
     * Sprawdzenie czy dany serwis uwierzytelniający jest dostępny
     * 
     * @param string $provider nazwa zewnętrznego serwisu
     * 
     * @return int
     */
    private function validateProvider(string $provider): int {

        $encrypter = new Encrypter;

        $providerTypes = DB::table('provider_types')->where('is_enabled', 1)->get();

        $provider = strtoupper($provider);

        foreach ($providerTypes as $pT) {
            if ($encrypter->decrypt($pT->name) == $provider) {
                $providerId = $pT->id;
                break;
            }
        }

        if (!isset($providerId)) {
            JsonResponse::sendError(
                AuthResponse::INVALID_PROVIDER,
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        return $providerId;
    }

    /**
     * Stworzenie ciasteczek JWT oraz REFRESH-TOKEN
     * 
     * @return void
     */
    private function prepareCookies(): void {

        /** @var User $user */
        $user = Auth::user();

        $encrypter = new Encrypter;

        $plainRefreshToken = $encrypter->generatePlainToken(64);
        $refreshToken = $encrypter->encryptToken($plainRefreshToken);

        $jwt = $user->createToken($encrypter->encrypt('JWT', 3));
        $plainJWT = $jwt->plainTextToken;
        $jwtId = $jwt->accessToken->getKey();

        DB::table('personal_access_tokens')
            ->where('id', $jwtId)
            ->update(['refresh_token' => $refreshToken]);

        JsonResponse::setCookie($plainJWT, 'JWT');
        JsonResponse::setCookie($plainRefreshToken, 'REFRESH-TOKEN');
    }

    /**
     * Zapisanie na serwerze avatara użytkownika pobranego z serwisu uwierzytelniającego
     * 
     * @param string $provider nazwa zewnętrznego serwisu
     * @param string $avatarPath adres URL do zdjecia profilowego z serwisu uwierzytelniającego
     * 
     * @return string
     */
    private function saveAvatar(string $provider, string $avatarUrl): string {

        $provider = strtoupper($provider);

        if ($provider == 'FACEBOOK') {
            if (env('FACEBOOK_MODE') == 'development') {
                $avatarUrlHeaders = get_headers($avatarUrl, 1);
                $avatarUrlLocation = $avatarUrlHeaders['Location'];
                $avatarUrlSeparators = explode('/', $avatarUrlLocation);
                $avatarUrlSeparatorsLength = count($avatarUrlSeparators);
                $avatarNewUrl = $avatarUrlSeparators[$avatarUrlSeparatorsLength-1];
                $avatarNewUrlSeparators = explode('?', $avatarNewUrl);
                $avatarFilename = $avatarNewUrlSeparators[0];
                $avatarFilenameLength = strlen($avatarFilename);
                $avatarFileExtension = '';

                for ($i=$avatarFilenameLength-1; $avatarFilename[$i] != '.'; $i--) {
                    $avatarFileExtension .= $avatarFilename[$i];
                }

                $avatarFileExtension = '.' . strrev($avatarFileExtension);

                $encrypter = new Encrypter;

                do {
                    $avatarFilename = $encrypter->generatePlainToken(32, $avatarFileExtension);
                    $avatarFilenameEncrypted = $encrypter->encryptToken($avatarFilename);
                    $avatarExists = DB::table('users')->where('avatar', $avatarFilenameEncrypted)->first();
                } while ($avatarExists);
            } else if (env('FACEBOOK_MODE') == 'live') {
                // TODO Uzupełnić zapisywanie zdjęcia z facebooka
            }
        } else if ($provider == 'GOOGLE') {
            // TODO Uzupełnić zapisywanie zdjęcia z google'a
        }

        $avatarContents = file_get_contents($avatarUrlLocation);
        Storage::put('avatars/' . $avatarFilename, $avatarContents);

        return $avatarFilename;
    }
}
