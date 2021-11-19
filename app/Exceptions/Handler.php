<?php

namespace App\Exceptions;

use App\Http\Responses\JsonResponse;
use App\Http\ErrorCodes\AuthErrorCode;
use App\Http\ErrorCodes\BaseErrorCode;
use App\Http\Libraries\FieldsConversion\FieldConversion;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
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

        $class = get_class($throwable);

        switch ($class) {

            case ValidationException::class:
                /** @var ValidationException $throwable */

                JsonResponse::sendError(
                    BaseErrorCode::FAILED_VALIDATION(),
                    FieldConversion::convertToCamelCase($throwable->errors())
                );
                break;

            case HttpException::class:
                /** @var HttpException $throwable */

                JsonResponse::sendError(
                    BaseErrorCode::PERMISSION_DENIED(),
                    FieldConversion::convertToCamelCase($throwable->getMessage())
                );
                break;

            case ClientException::class:
                /** @var ClientException $throwable */

                JsonResponse::sendError(
                    AuthErrorCode::INVALID_CREDENTIALS_PROVIDED(),
                    env('APP_DEBUG') ? FieldConversion::convertToCamelCase($throwable->getMessage()) : null
                );
                break;

            case ApiException::class:
                /** @var ApiException $throwable */

                JsonResponse::sendError(
                    $throwable->getErrorCode(),
                    FieldConversion::convertToCamelCase($throwable->getData()),
                    FieldConversion::convertToCamelCase($throwable->getMetadata()),
                );
                break;

            default:
                $throwable = json_encode($throwable);
                $throwable = json_decode($throwable, true);

                JsonResponse::sendError(
                    BaseErrorCode::INTERNAL_SERVER_ERROR(),
                    env('APP_DEBUG') ? FieldConversion::convertToCamelCase($throwable) : null
                );
                break;
        }
    }
}
