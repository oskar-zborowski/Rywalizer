<?php

namespace App\Http\ErrorCode;

use Symfony\Component\HttpFoundation\Response;

/**
 * Kody domyślnych odpowiedzi
 */
class BaseErrorCode {

    public static function FAILED_VALIDATION() { 
        return new ErrorCode('DEF1', 'FAILED_VALIDATION', Response::HTTP_BAD_REQUEST); 
    }

    public static function INTERNAL_SERVER_ERROR() { 
        return new ErrorCode('DEF2', 'INTERNAL_SERVER_ERROR', Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public static function PERMISSION_DENIED() {
        return new ErrorCode('DEF3', 'PERMISSION_DENIED', Response::HTTP_UNAUTHORIZED);
    }

    public static function LIMIT_EXCEEDED() {
        return new ErrorCode('DEF4', 'LIMIT_EXCEEDED', Response::HTTP_NOT_ACCEPTABLE);
    }

}
