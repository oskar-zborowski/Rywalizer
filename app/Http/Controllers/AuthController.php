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

        $jwt = $user->createToken('JWT')->plainTextToken;

        $cookie = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));

        return response([
            'message' => 'Register successful'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function login(Request $request) {

        if (!Auth::attempt($request->only('email', 'password'))) {
            return response([
                'message' => 'Invalid credentials!'
            ], Response::HTTP_UNAUTHORIZED);
        }
    
        /** @var User $user */
        $user = Auth::user();

        $jwt = $user->createToken('JWT')->plainTextToken;

        $cookie = cookie('JWT', $jwt, env('COOKIE_LIFETIME'));

        return response([
            'message' => 'Login successful'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function refresh(Request $request) {
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
            'message' => 'Logout successful'
        ], Response::HTTP_OK)->withCookie($cookie);
    }

    public function user(Request $request) {

        return response($request->user(), Response::HTTP_OK);
    }
}