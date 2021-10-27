<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ReplaceFieldNamesWithCamelCase
{
    public function handle(Request $request, Closure $next) {

        $response = $next($request);
        $contentArray = json_decode($response->getContent(), true);
        $fieldNames = [];

        foreach ($contentArray as $key => $value) {
            $fieldNames[Str::camel($key)] = $value;
        }

        $response->setContent(json_encode($fieldNames));

        return $response;
    }
}