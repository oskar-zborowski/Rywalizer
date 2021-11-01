<?php

namespace App\Http\Controllers;

use App\Http\Libraries\Encrypter;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
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

        $refreshToken = $encrypter->generateToken(42);

        $jwt = $user->createToken('JWT', [$refreshToken])->plainTextToken;

        $cookieJWT = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));
        $cookieRefreshToken = cookie('refreshToken', $refreshToken, env('COOKIE_LIFETIME'));

        return response([
            'message' => 'Register successful'
        ], Response::HTTP_OK)->withCookie($cookieJWT)->withCookie($cookieRefreshToken);
    }

    public function login(Request $request) {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }
    
        /** @var User $user */
        $user = Auth::user();

        $emailVerifiedAt = $request->user()->email_verified_at;
        $accountBlockedAt = $request->user()->account_blocked_at;
        $accountDeletedAt = $request->user()->account_deleted_at;

        if ($accountBlockedAt || $accountDeletedAt) {

            if ($accountBlockedAt) {
                $message = 'The account has been blocked!';
            } else if ($accountDeletedAt) {
                $message = 'The account has been submitted for deletion!';
            }

            return response([
                'message' => $message
            ], Response::HTTP_UNAUTHORIZED);
        }

        $jwt = $user->createToken('JWT')->plainTextToken;

        $cookie = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));

        if (!$emailVerifiedAt) {

            return response([
                'message' => 'Unverified email!'
            ], Response::HTTP_NOT_ACCEPTABLE)->withCookie($cookie);
        }

        return response([
            'message' => 'Logged in successfully'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function refreshToken(Request $request) {
        $request->user()->currentAccessToken()->delete();

        /** @var User $user */
        $user = Auth::user();

        $jwt = $user->createToken('JWT')->plainTextToken;

        $cookie = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));

        return response([
            'message' => 'Refreshing token successful'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function logout(Request $request) {

        $request->user()->currentAccessToken()->delete();
        $cookie = Cookie::forget('JWT');

        return response([
            'message' => 'Logged out successfully'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function user(Request $request) {
        return response($request->user(), Response::HTTP_OK);
    }
}