<?php

namespace App\Http\Middleware\Authenticate;

use App\Http\Libraries\Encrypter\Encrypter;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password as RulesPassword;

/**
 * Klasa wywoływana przed uwierzytelnieniem
 */
class BeforeAuthenticate
{
    /**
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next) {

        $loginURL = env('APP_URL') . '/api/login';
        $registerURL = env('APP_URL') . '/api/register';
        $forgotPasswordURL = env('APP_URL') . '/api/forgot-password';
        $resetPasswordURL = env('APP_URL') . '/api/reset-password';
        $verifyEmailURL = env('APP_URL') . '/api/email/verify';
        $updateUserURL = env('APP_URL') . '/api/user';

        $encrypter = new Encrypter;

        if ($request->url() == $loginURL ||
            $request->url() == $registerURL ||
            $request->url() == $forgotPasswordURL ||
            $request->url() == $updateUserURL)
        {
            $request->validate([
                'email' => 'string|email|max:254'
            ]);

            if ($request->url() != $updateUserURL) {
                $request->validate([
                    'email' => 'required'
                ]);
            }

            if ($request->email) {
                $encryptedEmail = $encrypter->encrypt($request->email, 254);
                $request->merge(['email' => $encryptedEmail]);
            }

            if ($request->url() == $forgotPasswordURL) {
                $request->validate([
                    'email' => 'exists:users'
                ]);
            }
        }

        if ($request->url() == $loginURL ||
            $request->url() == $registerURL ||
            $request->url() == $resetPasswordURL ||
            $request->url() == $updateUserURL)
        {
            $request->validate([
                'password' => 'nullable|string|between:8,20'
            ]);

            if ($request->url() != $updateUserURL) {
                $request->validate([
                    'password' => 'required'
                ]);
            }

            if ($request->url() != $loginURL) {
                
                $request->validate([
                    'password' => ['confirmed', RulesPassword::defaults()]
                ]);

                if ($request->password) {
                    $encryptedPassword = $encrypter->hash($request->password);
                    $request->merge(['password' => $encryptedPassword]);
                }
            }
        }
        
        if ($request->url() == $resetPasswordURL ||
            $request->url() == $verifyEmailURL)
        {
            $request->validate([
                'token' => 'required|string|alpha_num|size:48'
            ]);

            if ($request->token) {
                $encryptedToken = $encrypter->encryptToken($request->token);
                $request->merge(['token' => $encryptedToken]);
            }

            if ($request->url() == $resetPasswordURL) {
                $request->validate([
                    'token' => 'exists:password_resets',
                    'do_not_logout' => 'nullable|boolean'
                ]);
            } else {
                $request->validate([
                    'token' => 'exists:email_verifications'
                ]);
            }
        }

        if ($request->url() == $updateUserURL) {

            $request->validate([
                'telephone' => 'string|max:24',
                'facebook_profile' => 'string|url|max:254'
            ]);

            if ($request->telephone) {
                $encryptedTelephone = $encrypter->encrypt($request->telephone, 24);
                $request->merge(['telephone' => $encryptedTelephone]);
            }

            if ($request->facebook_profile) {
                $encryptedFacebookProfile = $encrypter->encrypt($request->facebook_profile, 254);
                $request->merge(['facebook_profile' => $encryptedFacebookProfile]);
            }
        }

        return $next($request);
    }
}
