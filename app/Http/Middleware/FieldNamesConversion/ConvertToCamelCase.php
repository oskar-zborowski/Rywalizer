<?php

namespace App\Http\Middleware\FieldNamesConversion;

use App\Http\Libraries\Http\JsonResponse;
use App\Http\Responses\DefaultResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ConvertToCamelCase
{
    public function handle(Request $request, Closure $next) {

        $response = $next($request);

        $exception = $response->exception;

        if ($exception instanceof ValidationException) {

            $contentArray = json_decode($response->getContent(), true);

            $fieldNames = [];

            foreach ($contentArray as $cA) {
                if (is_array($cA)) {
                    foreach ($cA as $k => $v) {
                        $fieldNames[Str::camel($k)] = $v;
                    }
                }
            }

            JsonResponse::sendError(
                DefaultResponse::FAILED_VALIDATION,
                Response::HTTP_BAD_REQUEST,
                [$fieldNames]
            );
        } else {
            JsonResponse::sendError(
                DefaultResponse::INTERNAL_SERVER_ERROR,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                env('APP_DEBUG') ? [$exception] : null
            );
        }
    }
}
