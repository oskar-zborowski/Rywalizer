<?php

namespace App\Http\Middleware\Authenticate;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    protected function redirectTo($request) {
        
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards) {

        if ($jwt = $request->cookie('JWT')) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
        } else {
            return response([
                'code' => 'A14',
                'message' => 'Unauthorized!'
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $this->authenticate($request, $guards);

        $accountBlockedAt = $request->user()->account_blocked_at;

        if ($accountBlockedAt) {
            $request->user()->currentAccessToken()->delete();
            $cookie = Cookie::forget('JWT');

            return response([
                'code' => 'A3',
                'message' => 'The account has been blocked!'
            ], Response::HTTP_UNAUTHORIZED)->withCookie($cookie);
        }

        return $next($request);
    }
}
