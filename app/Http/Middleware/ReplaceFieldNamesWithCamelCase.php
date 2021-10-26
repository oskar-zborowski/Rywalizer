<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReplaceFieldNamesWithCamelCase
{
    public function handle(Request $request, Closure $next) {

        $response = $next($request);

        // $response->setContent(Str::camel($response->getContent()));

        return $response;
    }
}