<?php

namespace App\Http\Middleware;

use App\Http\Libraries\Encrypter;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BeforeLoginAndRegister
{
    public function handle(Request $request, Closure $next) {
        if ($request->cookie('JWT')) {
            return response([
                'message' => 'You are already logged in!'
            ], Response::HTTP_NOT_ACCEPTABLE);
        }

        $request->validate([
            'email' => 'required|string|email|max:254',
            'password' => 'required|string|max:20',
        ]);

        $encrypter = new Encrypter;

        $request->merge(['email' => $encrypter->encrypt($request->input('email'), 254)]);

        return $next($request);
    }
}