<?php

namespace App\Http\ErrorCodes;

use Symfony\Component\HttpFoundation\Response;

/**
 * Kody odpowiedzi do procesu uwierzytelniania i jego pochodnych
 */
class AuthErrorCode
{
    public static function UNAUTHORIZED(): ErrorCode {
        return new ErrorCode('ATH1', 'UNAUTHORIZED', Response::HTTP_UNAUTHORIZED);
    }

    public static function INVALID_CREDENTIALS(): ErrorCode {
        return new ErrorCode('ATH2', 'INVALID CREDENTIALS', Response::HTTP_UNAUTHORIZED);
    }

    public static function UNVERIFIED_EMAIL(): ErrorCode {
        return new ErrorCode('ATH3', 'UNVERIFIED EMAIL', Response::HTTP_FORBIDDEN);
    }

    public static function ACOUNT_DELETED(): ErrorCode {
        return new ErrorCode('ATH4', 'ACOUNT DELETED', Response::HTTP_UNAUTHORIZED);
    }

    public static function ACOUNT_BLOCKED(): ErrorCode {
        return new ErrorCode('ATH5', 'ACOUNT BLOCKED', Response::HTTP_UNAUTHORIZED);
    }

    public static function ALREADY_LOGGED_IN(): ErrorCode {
        return new ErrorCode('ATH6', 'ALREADY LOGGED IN', Response::HTTP_NOT_ACCEPTABLE);
    }

    public static function EMAIL_ALREADY_VERIFIED(): ErrorCode {
        return new ErrorCode('ATH7', 'EMAIL ALREADY VERIFIED', Response::HTTP_NOT_ACCEPTABLE);
    }

    public static function INVALID_EMAIL_VERIFIFICATION_TOKEN(): ErrorCode {
        return new ErrorCode('ATH8', 'INVALID EMAIL VERIFIFICATION TOKEN', Response::HTTP_BAD_REQUEST);
    }

    public static function EMAIL_VERIFIFICATION_TOKEN_HAS_EXPIRED(): ErrorCode {
        return new ErrorCode('ATH9', 'EMAIL VERIFIFICATION TOKEN HAS EXPIRED', Response::HTTP_NOT_ACCEPTABLE);
    }

    public static function INVALID_PASSWORD_RESET_TOKEN(): ErrorCode {
        return new ErrorCode('ATH10', 'INVALID PASSWORD RESET TOKEN', Response::HTTP_BAD_REQUEST);
    }

    public static function PASSWORD_RESET_TOKEN_HAS_EXPIRED(): ErrorCode {
        return new ErrorCode('ATH11', 'PASSWORD RESET TOKEN HAS EXPIRED', Response::HTTP_NOT_ACCEPTABLE);
    }

    public static function INVALID_REFRESH_TOKEN(): ErrorCode {
        return new ErrorCode('ATH12', 'INVALID REFRESH TOKEN', Response::HTTP_UNAUTHORIZED);
    }

    public static function REFRESH_TOKEN_IS_STILL_ACTIVE(): ErrorCode {
        return new ErrorCode('ATH13', 'REFRESH TOKEN IS STILL ACTIVE', Response::HTTP_NOT_ACCEPTABLE);
    }

    public static function REFRESH_TOKEN_HAS_EXPIRED(): ErrorCode {
        return new ErrorCode('ATH14', 'REFRESH TOKEN HAS EXPIRED', Response::HTTP_UNAUTHORIZED);
    }

    public static function INVALID_PROVIDER(): ErrorCode {
        return new ErrorCode('ATH15', 'INVALID PROVIDER', Response::HTTP_BAD_REQUEST);
    }

    public static function INVALID_CREDENTIALS_PROVIDED(): ErrorCode {
        return new ErrorCode('ATH16', 'INVALID CREDENTIALS PROVIDED', Response::HTTP_UNAUTHORIZED);
    }

    public static function MISSING_USER_INFORMATION(): ErrorCode {
        return new ErrorCode('ATH17', 'MISSING USER INFORMATION', Response::HTTP_FORBIDDEN);
    }

    public static function WAIT_BEFORE_RETRYING(): ErrorCode {
        return new ErrorCode('ATH18', 'WAIT BEFORE RETRYING', Response::HTTP_NOT_ACCEPTABLE);
    }

    public static function WAIT_BEFORE_CHANGING_NAME(): ErrorCode {
        return new ErrorCode('ATH19', 'WAIT BEFORE CHANGING NAME', Response::HTTP_NOT_ACCEPTABLE);
    }
}
