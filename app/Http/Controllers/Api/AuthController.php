<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Http\JsonResponse;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\AuthResponse;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;

/**
 * Klasa odpowiedzialna za wszelkie kwestie związane z uwierzytelnianiem i jego pochodnymi
 */
class AuthController extends Controller
{
    /**
     * Logowanie użytkownika
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function login(Request $request, Encrypter $encrypter): void {

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
     * Rejestracja użytkownika
     * 
     * @param App\Http\Requests\Auth\RegisterRequest $request
     * @param App\Http\Libraries\Encrypter\Encrypter $encrypter
     * 
     * @return void
     */
    public function register(RegisterRequest $request, Encrypter $encrypter): void {

        $encryptedEmail = $request->input('email');
        $plainPassword = $request->input('password');

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
     * Wysyłka linku na maila do resetu hasła
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function forgotPassword(Request $request): void {

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            JsonResponse::sendSuccess();
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    /**
     * Reset hasła
     * 
     * @param Illuminate\Http\Request $request
     * 
     * @return void
     */
    public function resetPassword(Request $request): void {

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

            $user->sendEmailVerificationNotification();
    
            JsonResponse::sendSuccess();

        } else {
            $user->sendEmailVerificationNotification();
        }
    }

    /**
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

        Validator::make(['authentication_id' => $authenticationId], [
            'authentication_id' => 'required|string|between:1,254'
        ]);

        $externalAuthentication = DB::table('external_authentications')
            ->where('authentication_id', $encrypter->encrypt($authenticationId, 254))
            ->where('provider_type_id', $providerId)
            ->first();

        if (!$externalAuthentication) {

            if (filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
                Validator::make(['email' => $encrypter->encrypt($user->getEmail(), 254)], [
                    'email' => 'unique:users'
                ]);
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

            if ($user->getAvatar()) {
                if ($providerId == 1) {
                    $avatarUrl = $user->getAvatar();
                    $avatarUrlHeaders = get_headers($avatarUrl, 1);
                    $avatarUrlLocation = $avatarUrlHeaders['Location'];
                    $avatarUrlSeparators = explode('/', $avatarUrlLocation);
                    $avatarUrlSeparatorsLength = count($avatarUrlSeparators);
                    $avatarNewUrl = $avatarUrlSeparators[$avatarUrlSeparatorsLength-1];
                    $avatarNewUrlSeparators = explode('?', $avatarNewUrl);
                    $avatarFilename = $avatarNewUrlSeparators[0];
                    $avatarFileExtension = '';
                    $avatarFilenameLength = strlen($avatarFilename);
            
                    for ($i=$avatarFilenameLength-1; $avatarFilename[$i] != '.'; $i--) {
                        $avatarFileExtension .= $avatarFilename[$i];
                    }
            
                    $avatarFileExtension = '.' . strrev($avatarFileExtension);

                    do {
                        $avatarFilename = $encrypter->generatePlainToken(32, $avatarFileExtension);
                        $avatarFilenameEncrypted = $encrypter->encryptToken($avatarFilename);
                        $avatarExists = DB::table('users')->where('avatar', $avatarFilenameEncrypted)->first();
                    } while ($avatarExists);
                }

                $avatarContents = file_get_contents($avatarUrlLocation);
                Storage::put($avatarFilename, $avatarContents);
            }

            $newUser = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'avatar' => isset($avatarFilename) ? $avatarFilename : null,
                'email_verified_at' => filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL) ? now() : null
            ];

            if ($user->getEmail()) {
                $newUser['email'] = $user->getEmail();
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

        if (!$user->first_name ||
            !$user->last_name ||
            !$user->email ||
            !$user->gender_type_id ||
            !$user->role_type_id ||
            !$user->birth_date)
        {
            JsonResponse::sendError(
                AuthResponse::MISSING_USER_INFORMATION,
                Response::HTTP_FORBIDDEN,
                [$user]
            );
        }

        if (!$user->email_verified_at) {
            JsonResponse::sendError(
                AuthResponse::UNVERIFIED_EMAIL,
                Response::HTTP_UNAUTHORIZED,
            );
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

        $jwt = $user->createToken('JWT');
        $plainJWT = $jwt->plainTextToken;
        $jwtId = $jwt->accessToken->getKey();

        DB::table('personal_access_tokens')
            ->where('id', $jwtId)
            ->update(['refresh_token' => $refreshToken]);

        JsonResponse::setCookie($plainJWT, 'JWT');
        JsonResponse::setCookie($plainRefreshToken, 'REFRESH-TOKEN');
    }
}
