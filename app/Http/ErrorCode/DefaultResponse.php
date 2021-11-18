<?php

namespace App\Http\ErrorCode;

use Symfony\Component\HttpFoundation\Response;

/**
 * Kody domyślnych odpowiedzi
 */
class DefaultResponse {
    public static ErrorCode $FAILED_VALIDATION;
    public static ErrorCode $INTERNAL_SERVER_ERROR;
    public static ErrorCode $PERMISSION_DENIED;
    public static ErrorCode $LIMIT_EXCEEDED;
}


DefaultResponse::$FAILED_VALIDATION = new ErrorCode('DEF1', 'FAILED_VALIDATION', Response::HTTP_BAD_REQUEST);
DefaultResponse::$INTERNAL_SERVER_ERROR = new ErrorCode('DEF2', 'INTERNAL_SERVER_ERROR', Response::HTTP_INTERNAL_SERVER_ERROR);
DefaultResponse::$PERMISSION_DENIED = new ErrorCode('DEF3', 'PERMISSION_DENIED', Response::HTTP_UNAUTHORIZED);
DefaultResponse::$LIMIT_EXCEEDED = new ErrorCode('DEF4', 'LIMIT_EXCEEDED', Response::HTTP_NOT_ACCEPTABLE);
