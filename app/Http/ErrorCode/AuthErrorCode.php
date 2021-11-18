<?php

namespace App\Http\ErrorCode;

use Symfony\Component\HttpFoundation\Response;

/**
 * Kody odpowiedzi do procesów uwierzytelniania, autoryzacji i ich pochodnych
 */
class AuthErrorCode {

    public static function UNAUTHORIZED() {
        return new ErrorCode('ATH1', 'UNAUTHORIZED', Response::HTTP_UNAUTHORIZED);
    }

    public static function INVALID_CREDENTIALS() {
        return new ErrorCode('ATH2', 'INVALID_CREDENTIALS', Response::HTTP_UNAUTHORIZED);
    }

    public static function UNVERIFIED_EMAIL() {
        return new ErrorCode('ATH3', 'UNVERIFIED_EMAIL', Response::HTTP_FORBIDDEN);
    }

    public static function ACOUNT_DELETED() {
        return new ErrorCode('ATH4', 'ACOUNT_DELETED', Response::HTTP_UNAUTHORIZED);
    }

    public static function ACOUNT_BLOCKED() {
        return new ErrorCode('ATH5', 'ACOUNT_BLOCKED', Response::HTTP_UNAUTHORIZED);
    }

    public static function ALREADY_LOGGED_IN() {
        return new ErrorCode('ATH6', 'ALREADY_LOGGED_IN', Response::HTTP_UNAUTHORIZED);
    }

    public static function EMAIL_ALREADY_VERIFIED() {
        return new ErrorCode('ATH7', 'EMAIL_ALREADY_VERIFIED', Response::HTTP_NOT_ACCEPTABLE);
    }

    public static function INVALID_PASSWORD_RESET_TOKEN() {
        return new ErrorCode('ATH8', 'INVALID_PASSWORD_RESET_TOKEN', Response::HTTP_UNAUTHORIZED);
    }

    public static function INVALID_REFRESH_TOKEN() {
        return new ErrorCode('ATH9', 'INVALID_REFRESH_TOKEN', Response::HTTP_UNAUTHORIZED);
    }

    public static function REFRESH_TOKEN_IS_STILL_ACTIVE() {
        return new ErrorCode('ATH10', 'REFRESH_TOKEN_IS_STILL_ACTIVE', Response::HTTP_BAD_REQUEST);
    }

    public static function REFRESH_TOKEN_HAS_EXPIRED() {
        return new ErrorCode('ATH11', 'REFRESH_TOKEN_HAS_EXPIRED', Response::HTTP_BAD_REQUEST);
    }

    public static function INVALID_PROVIDER() {
        return new ErrorCode('ATH12', 'INVALID_PROVIDER', Response::HTTP_BAD_REQUEST);
    }

    public static function INVALID_CREDENTIALS_PROVIDED() {
        return new ErrorCode('ATH13', 'INVALID_CREDENTIALS_PROVIDED', Response::HTTP_BAD_REQUEST);
    }

    public static function MISSING_USER_INFORMATION() {
        return new ErrorCode('ATH14', 'MISSING_USER_INFORMATION', Response::HTTP_BAD_REQUEST);
    }

    public static function WAIT_BEFORE_RETRYING() {
        return new ErrorCode('ATH15', 'WAIT_BEFORE_RETRYING', Response::HTTP_BAD_REQUEST);
    }

}