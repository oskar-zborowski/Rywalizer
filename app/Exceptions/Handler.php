<?php

namespace App\Exceptions;

use App\Http\Libraries\Http\JsonResponse;
use App\Http\Responses\DefaultResponse;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
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
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $throwable) {

        if ($throwable instanceof ValidationException) {
            JsonResponse::sendError(
                DefaultResponse::FAILED_VALIDATION,
                Response::HTTP_BAD_REQUEST,
                JsonResponse::convertToCamelCase($throwable->errors())
            );
        } else {
            $throwable = json_encode($throwable);
            $throwable = json_decode($throwable, true);

            JsonResponse::sendError(
                DefaultResponse::INTERNAL_SERVER_ERROR,
                Response::HTTP_INTERNAL_SERVER_ERROR,
                env('APP_DEBUG') ? JsonResponse::convertToCamelCase($throwable) : null
            );
        }
    }
}
