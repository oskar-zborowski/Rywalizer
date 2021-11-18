<?php

namespace App\Http\ErrorCode;

use Symfony\Component\HttpFoundation\Response;

/**
 * Kody odpowiedzi do procesów uwierzytelniania, autoryzacji i ich pochodnych
 */
class AuthResponse {
    public static ErrorCode $UNAUTHORIZED;
    public static ErrorCode $INVALID_CREDENTIALS;
    public static ErrorCode $UNVERIFIED_EMAIL;
    public static ErrorCode $ACOUNT_DELETED;
    public static ErrorCode $ACOUNT_BLOCKED;
    public static ErrorCode $ALREADY_LOGGED_IN;
    public static ErrorCode $EMAIL_ALREADY_VERIFIED;
    public static ErrorCode $INVALID_PASSWORD_RESET_TOKEN;
    public static ErrorCode $INVALID_REFRESH_TOKEN;
    public static ErrorCode $REFRESH_TOKEN_IS_STILL_ACTIVE;
    public static ErrorCode $REFRESH_TOKEN_HAS_EXPIRED;
    public static ErrorCode $INVALID_PROVIDER;
    public static ErrorCode $INVALID_CREDENTIALS_PROVIDED;
    public static ErrorCode $MISSING_USER_INFORMATION;
    public static ErrorCode $WAIT_BEFORE_RETRYING;
}

AuthResponse::$UNAUTHORIZED = new ErrorCode('ATH1', 'UNAUTHORIZED', Response::HTTP_UNAUTHORIZED);
AuthResponse::$INVALID_CREDENTIALS = new ErrorCode('ATH2', 'INVALID_CREDENTIALS', Response::HTTP_UNAUTHORIZED);
AuthResponse::$UNVERIFIED_EMAIL = new ErrorCode('ATH3', 'UNVERIFIED_EMAIL', Response::HTTP_FORBIDDEN);
AuthResponse::$ACOUNT_DELETED = new ErrorCode('ATH4', 'ACOUNT_DELETED', Response::HTTP_UNAUTHORIZED);
AuthResponse::$ACOUNT_BLOCKED = new ErrorCode('ATH5', 'ACOUNT_BLOCKED', Response::HTTP_UNAUTHORIZED);
AuthResponse::$ALREADY_LOGGED_IN = new ErrorCode('ATH6', 'ALREADY_LOGGED_IN', Response::HTTP_UNAUTHORIZED);
AuthResponse::$EMAIL_ALREADY_VERIFIED = new ErrorCode('ATH7', 'EMAIL_ALREADY_VERIFIED', Response::HTTP_NOT_ACCEPTABLE);
AuthResponse::$INVALID_PASSWORD_RESET_TOKEN = new ErrorCode('ATH8', 'INVALID_PASSWORD_RESET_TOKEN', Response::HTTP_UNAUTHORIZED);
AuthResponse::$INVALID_REFRESH_TOKEN = new ErrorCode('ATH9', 'INVALID_REFRESH_TOKEN', Response::HTTP_UNAUTHORIZED);
AuthResponse::$REFRESH_TOKEN_IS_STILL_ACTIVE = new ErrorCode('ATH10', 'REFRESH_TOKEN_IS_STILL_ACTIVE', Response::HTTP_BAD_REQUEST);
AuthResponse::$REFRESH_TOKEN_HAS_EXPIRED = new ErrorCode('ATH11', 'REFRESH_TOKEN_HAS_EXPIRED', Response::HTTP_BAD_REQUEST);
AuthResponse::$INVALID_PROVIDER = new ErrorCode('ATH12', 'INVALID_PROVIDER', Response::HTTP_BAD_REQUEST);
AuthResponse::$INVALID_CREDENTIALS_PROVIDED = new ErrorCode('ATH13', 'INVALID_CREDENTIALS_PROVIDED', Response::HTTP_BAD_REQUEST);
AuthResponse::$MISSING_USER_INFORMATION = new ErrorCode('ATH14', 'MISSING_USER_INFORMATION', Response::HTTP_BAD_REQUEST);
AuthResponse::$WAIT_BEFORE_RETRYING = new ErrorCode('ATH15', 'WAIT_BEFORE_RETRYING', Response::HTTP_BAD_REQUEST);