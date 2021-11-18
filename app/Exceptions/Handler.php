<?php

namespace App\Exceptions;

use App\Http\Libraries\Http\JsonResponse;
use App\Http\ErrorCode\AuthErrorCode;
use App\Http\ErrorCode\BaseErrorCode;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler {
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register() {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Metoda przechwytująca wszystkie napotkane wyjątki i odpowiednio je parsująca przed wysłaniem odpowiedzi zwrotnej.
     * 
     * @param \Illuminate\Http\Request $request
     * @param Throwable $throwable
     * 
     * @return void
     */
    public function render($request, Throwable $throwable): void {
        if ($throwable instanceof ValidationException) {
            JsonResponse::sendError(
                BaseErrorCode::$FAILED_VALIDATION,
                JsonResponse::convertToCamelCase($throwable->errors())
            );
        } else if ($throwable instanceof HttpException) {
            JsonResponse::sendError(
                BaseErrorCode::$PERMISSION_DENIED,
                JsonResponse::convertToCamelCase([$throwable->getMessage()])
            );
        } else if ($throwable instanceof ClientException) {
            JsonResponse::sendError(
                AuthErrorCode::$INVALID_CREDENTIALS_PROVIDED,
                env('APP_DEBUG') ? JsonResponse::convertToCamelCase([$throwable->getMessage()]) : null
            );
        } else if ($throwable instanceof ApiException) {
            JsonResponse::sendError(
                $throwable->getErrorCode(),
                $throwable->getData(),
                $throwable->getMetadata(),
            );
        } else {
            $throwable = json_encode($throwable);
            $throwable = json_decode($throwable, true);

            JsonResponse::sendError(
                BaseErrorCode::$INTERNAL_SERVER_ERROR,
                env('APP_DEBUG') ? JsonResponse::convertToCamelCase($throwable) : null
            );
        }
    }
}
