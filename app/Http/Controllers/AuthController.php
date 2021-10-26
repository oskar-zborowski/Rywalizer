<?php

namespace App\Http\Controllers;

use App\Http\Libraries\Encrypter;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request, Encrypter $encrypter) {

        try {
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

        } catch (Exception $e) {
            return response([
                'message' => 'An unexpected error occurred during registration!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(Request $request) {

        try {
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

        } catch (Exception $e) {
            return response([
                'message' => 'An unexpected error occurred during registration!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request) {

        try {
            $request->user()->currentAccessToken()->delete();
            $cookie = Cookie::forget('JWT');
    
            return response([
                'message' => 'Logout successful'
            ], Response::HTTP_OK)->withCookie($cookie);

        } catch (Exception $e) {
            return response([
                'message' => 'An unexpected error occurred during registration!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function user() {
        
        try {
            $user = Auth::user();

            // return response($user, Response::HTTP_OK);

            return response([
                'firstName' => $user->first_name,
                'lastName' => $user->last_name,
                'email' => $user->email != null ? $user->email : null,
                'avatar' => $user->avatar != null ? $user->avatar : null,
                'genderType' => isset($user->genderType->name) ? $user->genderType->name : null,
                'roleType' => $user->roleType->name,
                'birthDate' => $user->birth_date,
            ], Response::HTTP_OK);

        } catch (Exception $e) {
            return response([
                'message' => 'An unexpected error occurred during registration!'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}