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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

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
    public function login(Request $request): void {

        if (!Auth::attempt($request->only('email', 'password'))) {
            JsonResponse::sendError(
                AuthResponse::INVALID_CREDENTIALS,
                Response::HTTP_UNAUTHORIZED
            );
        }
    
        /** @var User $user */
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

        /** @var User $user */
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

        /** @var User $user */
        $user = Auth::loginUsingId($userId);

        $this->prepareCookies();

        JsonResponse::sendSuccess([$user]);
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
    private function prepareCookies() {

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
