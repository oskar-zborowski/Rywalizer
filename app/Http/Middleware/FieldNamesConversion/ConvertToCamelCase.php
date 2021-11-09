<?php

namespace App\Http\Middleware\FieldNamesConversion;

use App\Http\Libraries\Http\JsonResponse;
use App\Http\Responses\DefaultResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ConvertToCamelCase
{
    public function handle(Request $request, Closure $next) {

        $response = $next($request);

        $exception = $response->exception;

        if ($exception instanceof ValidationException) {

            $contentArray = json_decode($response->getContent(), true);

            JsonResponse::sendError(
                DefaultResponse::FAILED_VALIDATION,
                Response::HTTP_BAD_REQUEST,
                JsonResponse::convertToCamelCase($contentArray, 1)
            );
        } else {
            $exception = json_encode($exception);
            $exception = json_decode($exception, true);

            JsonResponse::sendError(
                DefaultResponse::INTERNAL_SERVER_ERROR,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                env('APP_DEBUG') ? JsonResponse::convertToCamelCase($exception) : null
            );
        }
    }
}
