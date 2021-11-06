<?php

namespace App\Http\Middleware\Authenticate;

use App\Http\Libraries\Http\JsonResponse;
use App\Http\Responses\AuthResponse;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
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
            JsonResponse::sendError(
                AuthResponse::UNAUTHORIZED,
                Response::HTTP_UNAUTHORIZED
            );
        }
        
        $this->authenticate($request, $guards);

        $accountBlockedAt = $request->user()->account_blocked_at;

        if ($accountBlockedAt) {
            $request->user()->currentAccessToken()->delete();
            
            JsonResponse::deleteCookie();
            JsonResponse::sendError(
                AuthResponse::ACOUNT_BLOCKED,
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}
