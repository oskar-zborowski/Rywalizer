<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Libraries\Http\JsonResponse;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Responses\AuthResponse;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request) {

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

        $jwt = $user->createToken('JWT')->plainTextToken;
        JsonResponse::setCookie($jwt);

        if (!$emailVerifiedAt) {
            JsonResponse::sendError(
                AuthResponse::UNVERIFIED_EMAIL,
                Response::HTTP_FORBIDDEN
            );
        }

        JsonResponse::sendSuccess([$user]);
    }

    public function register(RegisterRequest $request, Encrypter $encrypter) {

        $encodedEmail = $request->input('email');
        $plainPassword = $request->input('password');

        $request->merge(['email' => $encrypter->decrypt($request->input('email'))]);
        $request->merge(['password' => $encrypter->hash($request->input('password'))]);

        User::create($request->only('first_name', 'last_name', 'email', 'password', 'gender_type_id', 'birth_date'));

        Auth::attempt([
            'email' => $encodedEmail,
            'password' => $plainPassword
        ]);

        /** @var User $user */
        $user = Auth::user();

        $jwt = $user->createToken('JWT')->plainTextToken;
        JsonResponse::setCookie($jwt);

        $this->sendVerificationEmail($request, true);

        JsonResponse::sendError(
            AuthResponse::UNVERIFIED_EMAIL,
            Response::HTTP_FORBIDDEN,
            [$user]
        );
    }

    public function forgotPassword(Request $request) {

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            JsonResponse::sendSuccess();
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function resetPassword(Request $request) {

        $status = Password::reset(
            $request->only('password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();

                $user->tokens()->delete();

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

    public function sendVerificationEmail(Request $request, bool $afterRegistartion = false) {

        if (!$afterRegistartion) {
            if ($request->user()->hasVerifiedEmail()) {
                JsonResponse::sendError(
                    AuthResponse::EMAIL_ALREADY_VERIFIED,
                    Response::HTTP_NOT_ACCEPTABLE
                );
            }

            $request->user()->sendEmailVerificationNotification();
    
            JsonResponse::sendSuccess();
        } else {
            $request->user()->sendEmailVerificationNotification();
        }
    }

    public function verify(EmailVerificationRequest $request) {
        if ($request->user()->hasVerifiedEmail()) {
            JsonResponse::sendError(
                AuthResponse::EMAIL_ALREADY_VERIFIED,
                Response::HTTP_NOT_ACCEPTABLE
            );
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        JsonResponse::sendSuccess();
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        JsonResponse::deleteCookie();
        JsonResponse::sendSuccess();
    }

    public function refreshToken() {

        // TODO Całe do poprawy
        
        // $request->user()->currentAccessToken()->delete();

        // /** @var User $user */
        // $user = Auth::user();

        // $jwt = $user->createToken('JWT')->plainTextToken;

        // $cookie = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));

        // return response([
        //     'message' => 'Refreshing token successful'
        // ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function user(Request $request) {
        JsonResponse::sendSuccess([$request->user()]);
    }
}
