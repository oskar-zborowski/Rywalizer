<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Libraries\Encrypter\Encrypter;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function login(Request $request) {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'code' => 'A2',
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }
    
        /** @var User $user */
        $user = Auth::user();

        $emailVerifiedAt = $user->email_verified_at;
        $accountBlockedAt = $user->account_blocked_at;
        $accountDeletedAt = $user->account_deleted_at;

        if ($accountBlockedAt) {
            return response([
                'code' => 'A3',
                'message' => 'The account has been blocked!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        if ($accountDeletedAt) {
            return response([
                'code' => 'A4',
                'message' => 'The account has been submitted for deletion!'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $jwt = $user->createToken('JWT')->plainTextToken;

        $cookie = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));

        if (!$emailVerifiedAt) {
            return response([
                'code' => 'A5',
                'message' => 'Unverified email!'
            ], Response::HTTP_NOT_ACCEPTABLE)->withCookie($cookie);
        }

        return response([
            'code' => 'A6',
            'message' => 'Logged in successfully'
        ], Response::HTTP_OK)->withCookie($cookie);
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

        $cookie = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));

        $this->sendVerificationEmail($request);

        return response([
            'code' => 'A5',
            'message' => 'Register successful'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function forgotPassword(Request $request) {

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response([
                'code' => 'A7',
                'message' => __($status)
            ], Response::HTTP_OK);
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }

    public function resetPassword(Request $request) {
        
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password)
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'code' => 'A8',
                'message' => __($status)
            ], Response::HTTP_OK);
        }

        return response([
            'code' => 'A9',
            'message' => __($status)
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function sendVerificationEmail(Request $request) {

        if ($request->user()->hasVerifiedEmail()) {
            return response([
                'code' => 'A10',
                'message' => 'Email already verified!'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $request->user()->sendEmailVerificationNotification();

        return response([
            'code' => 'A11',
            'message' => 'Verification link sent'
        ], Response::HTTP_OK);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response([
                'code' => 'A10',
                'message' => 'Email already verified!'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response([
            'code' => 'A12',
            'message' => 'Email has been verified'
        ], Response::HTTP_OK);
    }

    public function logout(Request $request) {

        $request->user()->currentAccessToken()->delete();
        $cookie = Cookie::forget('JWT');

        return response([
            'code' => 'A13',
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function refreshToken(Request $request) {

        //TODO Całe do poprawy
        
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
        return response($request->user(), Response::HTTP_OK);
    }
}