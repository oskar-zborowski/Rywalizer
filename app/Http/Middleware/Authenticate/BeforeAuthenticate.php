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
     * 
     * @return void
     */
    public function handle(Request $request, Closure $next) {

        $loginURL = env('APP_URL') . '/api/login';
        $forgotPasswordURL = env('APP_URL') . '/api/forgot-password';
        $resetPasswordURL = env('APP_URL') . '/api/reset-password';
        $refreshTokenURL = env('APP_URL') . '/api/refresh-token';

        if ($request->url() != $resetPasswordURL && $request->url() != $refreshTokenURL) {
            $request->validate([
                'email' => 'required|string|email|max:254'
            ]);
        }

        if ($request->url() != $forgotPasswordURL && $request->url() != $refreshTokenURL) {

            $request->validate([
                'password' => 'required|string|between:8,20'
            ]);

            if ($request->url() != $loginURL) {
                
                $request->validate([
                    'password' => ['confirmed', RulesPassword::defaults()]
                ]);

                if ($request->url() == $resetPasswordURL) {
                    $request->validate([
                        'token' => 'required|string|alpha_num|size:64'
                    ]);
                }
            }
        }

        if ($request->url() != $resetPasswordURL && $request->url() != $refreshTokenURL) {
            $encrypter = new Encrypter;
            $request->merge(['email' => $encrypter->encrypt($request->input('email'), 254)]);
        }

        return $next($request);
    }
}
