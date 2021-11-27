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

        if ($request->url() == $loginURL || $request->url() == $registerURL || $request->url() == $forgotPasswordURL) {
            $request->validate([
                'email' => 'required|string|email|max:254'
            ]);
            
            $encrypter = new Encrypter;
            $request->merge(['email' => $encrypter->encrypt($request->email, 254)]);
        }

        if ($request->url() == $loginURL || $request->url() == $registerURL || $request->url() == $resetPasswordURL) {
            $request->validate([
                'password' => 'required|string|between:8,20'
            ]);
        }

        if ($request->url() == $registerURL || $request->url() == $resetPasswordURL) {  
            $request->validate([
                'password' => ['confirmed', RulesPassword::defaults()]
            ]);
        }
        
        if ($request->url() == $resetPasswordURL) {
            $request->validate([
                'token' => 'required|string|alpha_num|size:48',
                'do_not_logout' => 'required|boolean'
            ]);
        }

        return $next($request);
    }
}
