<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next, ...$guards)
    {
        if ($jwt = $request->cookie('JWT')) {
            $request->headers->set('Authorization', 'Bearer ' . $jwt);
        } else {
            return response([
                'message' => 'Unauthorized!'
            ], Response::HTTP_UNAUTHORIZED);
        }
        
        $this->authenticate($request, $guards);

        if ($request->url() != env('APP_URL') . '/api/logout') {
            $emailVerifiedAt = $request->user()->email_verified_at;
            $accountBlockedAt = $request->user()->account_blocked_at;

            if ($accountBlockedAt) {

                $request->user()->currentAccessToken()->delete();
                $cookie = Cookie::forget('JWT');

                return response([
                    'message' => 'The account has been blocked!'
                ], Response::HTTP_UNAUTHORIZED)->withCookie($cookie);
                
            } else if (!$emailVerifiedAt) {

                return response([
                    'message' => 'Unverified email!'
                ], Response::HTTP_NOT_ACCEPTABLE);
            }
        }

        return $next($request);
    }
}