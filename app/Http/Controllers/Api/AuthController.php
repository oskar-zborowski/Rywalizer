<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Responses\JsonResponse;
use App\Http\Requests\Auth\FillMissingUserInfoRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Models\User;
use App\Exceptions\ApiException;
use App\Mail\VerificationEmail;
use App\Models\EmailVerification;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
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

        /** @var User $user */
        $user = Auth::user();

        $accountDeletedAt = $user->account_deleted_at;
        $accountBlockedAt = $user->account_blocked_at;

        if ($accountBlockedAt) {
            $user->tokens()->delete();
            throw new ApiException(AuthErrorCode::ACOUNT_BLOCKED());
        }

        if ($accountDeletedAt) {
            $user->tokens()->delete();
            throw new ApiException(AuthErrorCode::ACOUNT_DELETED());
        }

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

        $encryptedEmail = $request->email;
        $plainPassword = $request->password;

        $request->merge(['email' => $encrypter->decrypt($encryptedEmail)]);
        $request->merge(['password' => $encrypter->hash($plainPassword)]);

        User::create($request->only('first_name', 'last_name', 'email', 'password', 'gender_type_id', 'birth_date'));

        Auth::attempt([
            'email' => $encryptedEmail,
            'password' => $plainPassword
        ]);

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

        $emailSendingCounter = 0;

        $passwordResetToken = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if ($passwordResetToken) {

            $emailSendingCounter = $passwordResetToken->email_sending_counter;

            if ($emailSendingCounter == 255) {
                throw new ApiException(BaseErrorCode::LIMIT_EXCEEDED());
            }

            $now = date('Y-m-d H:i:s');
            $waitingDate = date('Y-m-d H:i:s', strtotime('+' . env('PAUSE_BEFORE_RETRYING')*60 . ' seconds', strtotime($passwordResetToken->created_at)));

            if ($now <= $waitingDate) {
                throw new ApiException(AuthErrorCode::WAIT_BEFORE_RETRYING());
            }

            DB::table('password_resets')
                ->where('id', $passwordResetToken->id)
                ->delete();
        }

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {

            $plainEmail = $encrypter->decrypt($request->email);

            DB::table('password_resets')
                ->where('email', $plainEmail)
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

        $passwordReset = null;

        foreach ($resetTokens as $rT) {

            if ($hasher->check($request->token, $rT->token)) {

                $passwordReset = DB::table('password_resets')
                    ->where('id', $rT->id)
                    ->first();

                $plainEmail = $encrypter->decrypt($rT->email);

                DB::table('password_resets')
                    ->where('id', $rT->id)
                    ->update(['email' => $plainEmail]);
                
                break;
            }
        }

        if (!$passwordReset) {
            throw new ApiException(AuthErrorCode::INVALID_PASSWORD_RESET_TOKEN());
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

        DB::table('password_resets')
            ->where('id', $passwordReset->id)
            ->delete();

        $now = date('Y-m-d H:i:s');
        $expirationDate = date('Y-m-d H:i:s', strtotime('+' . env('EMAIL_TOKEN_LIFETIME') . ' minutes', strtotime($passwordReset->created_at)));

        if ($now > $expirationDate) {
            throw new ApiException(AuthErrorCode::PASSWORD_RESET_TOKEN_HAS_EXPIRED());
        }

        $encryptedEmail = $encrypter->encrypt($plainEmail);

        DB::table('password_resets')
            ->where('id', $passwordReset->id)
            ->update(['email' => $encryptedEmail]);
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

        $emailSendingCounter = 0;

        if (!$afterRegistartion) {

            if ($user->hasVerifiedEmail()) {
                throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
            }

            $emailVerification = DB::table('email_verifications')
                ->where('user_id', $user->id)
                ->first();

            if ($emailVerification) {

                $emailSendingCounter = $emailVerification->email_sending_counter;

                if ($emailSendingCounter == 255) {
                    throw new ApiException(BaseErrorCode::LIMIT_EXCEEDED());
                }

                $now = date('Y-m-d H:i:s');
                $waitingDate = date('Y-m-d H:i:s', strtotime('+' . env('PAUSE_BEFORE_RETRYING')*60 . ' seconds', strtotime($emailVerification->updated_at)));
        
                if ($now <= $waitingDate && $emailVerification->email_sending_counter > 1) {
                    throw new ApiException(AuthErrorCode::WAIT_BEFORE_RETRYING());
                }
            }
        }

        $encrypter = new Encrypter;

        $plainToken = $encrypter->generatePlainToken(64);
        $encryptedToken = $encrypter->encryptToken($plainToken);

        EmailVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'token' => $encryptedToken,
                'email_sending_counter' => $emailSendingCounter+1,
            ]
        );

        $url = 'https://spa.test/email_verify?token=' . $plainToken; // TODO Poprawić na prawidłowy URL
        Mail::to($user)->send(new VerificationEmail($url));

        if (!$afterRegistartion) {
            JsonResponse::sendSuccess();
        }
    }

    /**
     * #### `PUT` `/api/verify-email/{token}`
     * Weryfikacja maila
     * 
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function verifyEmail(string $token = null, Encrypter $encrypter): void {

        /** @var User $user */
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            throw new ApiException(AuthErrorCode::EMAIL_ALREADY_VERIFIED());
        }

        if (!$token) {
            throw new ApiException(AuthErrorCode::INVALID_EMAIL_VERIFIFICATION_TOKEN());
        }

        $encryptedToken = $encrypter->encryptToken($token);

        $emailVerification = DB::table('email_verifications')
            ->where('token', $encryptedToken)
            ->first();

        if (!$emailVerification) {
            throw new ApiException(AuthErrorCode::INVALID_EMAIL_VERIFIFICATION_TOKEN());
        }

        DB::table('email_verifications')
            ->where('id', $emailVerification->id)
            ->delete();

        $now = date('Y-m-d H:i:s');
        $expirationDate = date('Y-m-d H:i:s', strtotime('+' . env('EMAIL_TOKEN_LIFETIME') . ' minutes', strtotime($emailVerification->updated_at)));

        if ($now > $expirationDate) {
            throw new ApiException(AuthErrorCode::EMAIL_VERIFIFICATION_TOKEN_HAS_EXPIRED());
        }

        $user->markEmailAsVerified();

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

        if ($plainRefreshToken = $request->cookie('REFRESH-TOKEN')) {

            $refreshToken = $encrypter->encryptToken($plainRefreshToken);

            $personalAccessToken = DB::table('personal_access_tokens')
                ->where('refresh_token', $refreshToken)
                ->first();

            if ($personalAccessToken) {

                $personalAccessTokenId = $personalAccessToken->id;

                DB::table('personal_access_tokens')
                    ->where('id', $personalAccessTokenId)
                    ->delete();
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

        $providerId = $this->validateProvider($provider);

        /** @var \Laravel\Socialite\Two\AbstractProvider */
        $driver = Socialite::driver($provider);

        $user = $driver->stateless()->user();

        $authenticationId = $user->getId();
        $encryptedAuthenticationId = $authenticationId ? $encrypter->encrypt($authenticationId, 254) : null;

        if (!$authenticationId || strlen($authenticationId) < 1 || strlen($authenticationId) > 254) {
            throw new ApiException(
                AuthErrorCode::INVALID_CREDENTIALS_PROVIDED(),
                ['authentication_id' => ['The provider returned an invalid id.']] // TODO Zmienić kiedy pojawią się langi
            );
        }

        $externalAuthentication = DB::table('external_authentications')
            ->where('authentication_id', $encryptedAuthenticationId)
            ->where('provider_type_id', $providerId)
            ->first();

        if (!$externalAuthentication) {

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {

                $encryptedEmail = $encrypter->encrypt($user->getEmail(), 254);

                $externalAuthentication = DB::table('users')
                    ->where('email', $encryptedEmail)
                    ->first();

                if ($externalAuthentication) {
                    throw new ApiException(
                        BaseErrorCode::FAILED_VALIDATION(),
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

            if ($user->getAvatar()) { //TODO Sprawdzić wariant co jest zwracane kiedy użytkownik nie ma ustawionego zdjęcia profilowego
                $avatarFilename = $this->saveAvatar($provider, $user->getAvatar());
            }

            $newUser = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'avatar' => isset($avatarFilename) ? $avatarFilename : null,
            ];

            if (isset($encryptedEmail)) {
                $newUser['email'] = $user->getEmail();
                $newUser['email_verified_at'] = now();
            }

            $createUser = User::create($newUser);

            $createUser->externalAuthentication()->create([
                'authentication_id' => $encryptedAuthenticationId,
                'provider_type_id' => $providerId
            ]);

            Auth::loginUsingId($createUser->id);

        } else {
            Auth::loginUsingId($externalAuthentication->user_id);
        }

        /** @var User $user */
        $user = Auth::user();

        DB::table('users')
            ->where('id', $user->id)
            ->update([
                'last_logged_in' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        $this->checkMissingUserInfo(true);
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

        $plainEmail = $request->email;
        $encryptedBirthDate = $request->birth_date ? $encrypter->encrypt($request->birth_date, 10) : null;

        if (!$user->email && $plainEmail) {

            $encryptedEmail = $encrypter->encrypt($plainEmail, 254);

            $request->merge(['email' => $encryptedEmail]);
            $request->validate([
                'email' => 'unique:users'
            ]);
        }

        $supplementaryInfo = null;

        if (!$user->email && isset($encryptedEmail)) {
            $supplementaryInfo['email'] = $encryptedEmail;
        }

        if (!$user->birth_date && $encryptedBirthDate) {
            $supplementaryInfo['birth_date'] = $encryptedBirthDate;
        }

        if (!$user->gender_type_id && $request->gender_type_id) {
            $supplementaryInfo['gender_type_id'] = $request->gender_type_id;
        }

        if (!$user->avatar && $request->avatar) {
            // TODO Zrobić wgrywanie i zapisywanie zdjęć przez formularz (wykorzystać metodę saveAvatar)
            $supplementaryInfo['avatar'] = $encrypter->encrypt($request->avatar, 48);
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

        $this->checkMissingUserInfo();
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
            throw new ApiException(AuthErrorCode::INVALID_PROVIDER());
        }

        return $providerId;
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

        // TODO Zamienić na switch case'a

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
                    $avatarFilename = $encrypter->generatePlainToken(64, $avatarFileExtension);
                    $avatarFilenameEncrypted = $encrypter->encryptToken($avatarFilename);
                    $avatarExists = DB::table('users')->where('avatar', $avatarFilenameEncrypted)->first();
                } while ($avatarExists);
                
            } else if (env('FACEBOOK_MODE') == 'live') {
                // TODO Uzupełnić zapisywanie zdjęcia z facebooka
            }
        } else if ($provider == 'GOOGLE') {
            // TODO Uzupełnić zapisywanie zdjęcia z google'a
        } else if ($provider == 'FORM') {
            // TODO Uzupełnić zapisywanie zdjęcia z formularza
        }

        $avatarContents = file_get_contents($avatarUrlLocation);
        Storage::put('avatars/' . $avatarFilename, $avatarContents);

        return $avatarFilename;
    }

    /**
     * Sprawdzenie brakujących informacji o użytkowniku i zwrócenie właściwych informacji
     * 
     * @param bool $withTokens flaga określająca czy mają zostać utworzone tokeny autoryzacyjne
     * 
     * @return void
     */
    private function checkMissingUserInfo($withTokens = false): void {

        /** @var User $user */
        $user = Auth::user();

        $emailVerifiedAt = $user->email_verified_at;

        $missingInfo = null;

        if (!$user->email) {
            $missingInfo['required']['email'] = ['The email field is missing.']; // TODO Zmienić kiedy pojawią się langi
        }

        if (!$user->birth_date) {
            $missingInfo['required']['birth_date'] = ['The birth date field is missing.']; // TODO Zmienić kiedy pojawią się langi
        }

        if (!$user->avatar) {
            $missingInfo['optional']['avatar'] = ['The avatar field is missing.']; // TODO Zmienić kiedy pojawią się langi
        }

        if (!$user->gender_type_id) {
            $missingInfo['optional']['gender_type_id'] = ['The gender type id field is missing.']; // TODO Zmienić kiedy pojawią się langi
        }

        if ($withTokens) {
            JsonResponse::prepareCookies();
        }

        if (isset($missingInfo['required']) || !$emailVerifiedAt) {
            throw new ApiException(
                $emailVerifiedAt ? AuthErrorCode::MISSING_USER_INFORMATION() : AuthErrorCode::UNVERIFIED_EMAIL(),
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
