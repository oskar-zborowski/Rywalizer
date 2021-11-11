<?php

namespace App\Http\Middleware\FieldNamesConversion;

use App\Http\Libraries\Http\JsonRequest;
use Closure;
use Illuminate\Http\Request;

/**
 * Klasa przeprowadzająca konwersję przychodzącego żądania na formę snake_case
 */
class ConvertToSnakeCase
{
    /**
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     */
    public function handle(Request $request, Closure $next) {

        $fieldNames = JsonRequest::convertToSnakeCase($request->all());

        if ($fieldNames) {
            $request->replace($fieldNames);
        }

        return $next($request);
    }
}
