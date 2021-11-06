<?php

namespace App\Http\Middleware\FieldNamesConversion;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConvertToSnakeCase
{
    public function handle(Request $request, Closure $next) {

        $fieldNames = [];

        foreach ($request->all() as $key => $value) {
            $fieldNames[Str::snake($key)] = $value;
        }

        $request->replace($fieldNames);

        return $next($request);
    }
}