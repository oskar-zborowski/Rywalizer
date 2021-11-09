<?php

namespace App\Http\Middleware\FieldNamesConversion;

use App\Http\Libraries\Http\JsonRequest;
use Closure;
use Illuminate\Http\Request;

class ConvertToSnakeCase
{
    public function handle(Request $request, Closure $next) {

        $fieldNames = JsonRequest::convertToSnakeCase($request->all());

        if ($fieldNames) {
            $request->replace($fieldNames);
        }

        return $next($request);
    }
}
