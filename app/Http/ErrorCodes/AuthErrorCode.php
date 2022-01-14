<?php

namespace App\Http\ErrorCodes;

use Symfony\Component\HttpFoundation\Response;

/**
 * Kody odpowiedzi do procesów uwierzytelniania i autoryzacji
 */
class AuthErrorCode
{
    public static function UNAUTHORIZED(): ErrorCode {
        return new ErrorCode('ATH1', 'UNAUTHORIZED', Response::HTTP_UNAUTHORIZED);
    }

    public static function INVALID_REFRESH_TOKEN(): ErrorCode {
        return new ErrorCode('ATH2', 'INVALID REFRESH TOKEN', Response::HTTP_UNAUTHORIZED);
    }

    public static function REFRESH_TOKEN_HAS_EXPIRED(): ErrorCode {
        return new ErrorCode('ATH3', 'REFRESH TOKEN HAS EXPIRED', Response::HTTP_UNAUTHORIZED);
    }

    public static function REFRESH_TOKEN_IS_STILL_ACTIVE(): ErrorCode {
        return new ErrorCode('ATH4', 'REFRESH TOKEN IS STILL ACTIVE', Response::HTTP_FORBIDDEN);
    }

    public static function INVALID_CREDENTIALS(): ErrorCode {
        return new ErrorCode('ATH5', 'INVALID CREDENTIALS', Response::HTTP_UNAUTHORIZED);
    }

    public static function INVALID_PROVIDER(): ErrorCode {
        return new ErrorCode('ATH6', 'INVALID PROVIDER', Response::HTTP_BAD_REQUEST);
    }

    public static function INVALID_CREDENTIALS_PROVIDED(): ErrorCode {
        return new ErrorCode('ATH7', 'INVALID CREDENTIALS PROVIDED', Response::HTTP_UNAUTHORIZED);
    }

    public static function ACOUNT_BLOCKED(): ErrorCode {
        return new ErrorCode('ATH8', 'ACOUNT BLOCKED', Response::HTTP_UNAUTHORIZED);
    }

    public static function ACOUNT_DELETED(): ErrorCode {
        return new ErrorCode('ATH9', 'ACOUNT DELETED', Response::HTTP_UNAUTHORIZED);
    }

    public static function UNVERIFIED_EMAIL(): ErrorCode {
        return new ErrorCode('ATH10', 'UNVERIFIED EMAIL', Response::HTTP_OK);
    }

    public static function EMPTY_EMAIL(): ErrorCode {
        return new ErrorCode('ATH11', 'EMPTY EMAIL', Response::HTTP_FORBIDDEN);
    }

    public static function EMAIL_ALREADY_VERIFIED(): ErrorCode {
        return new ErrorCode('ATH12', 'EMAIL ALREADY VERIFIED', Response::HTTP_FORBIDDEN);
    }

    public static function ALREADY_LOGGED_IN(): ErrorCode {
        return new ErrorCode('ATH13', 'ALREADY LOGGED IN', Response::HTTP_FORBIDDEN);
    }

    public static function ALREADY_LOGGED_OUT(): ErrorCode {
        return new ErrorCode('ATH14', 'ALREADY LOGGED OUT', Response::HTTP_FORBIDDEN);
    }

    public static function INVALID_PASSWORD_RESET_TOKEN(): ErrorCode {
        return new ErrorCode('ATH15', 'INVALID PASSWORD RESET TOKEN', Response::HTTP_BAD_REQUEST);
    }

    public static function PASSWORD_RESET_TOKEN_HAS_EXPIRED(): ErrorCode {
        return new ErrorCode('ATH16', 'PASSWORD RESET TOKEN HAS EXPIRED', Response::HTTP_BAD_REQUEST);
    }

    public static function INVALID_RESTORE_ACCOUNT_TOKEN(): ErrorCode {
        return new ErrorCode('ATH17', 'INVALID RESTORE ACCOUNT TOKEN', Response::HTTP_BAD_REQUEST);
    }

    public static function RESTORE_ACCOUNT_TOKEN_HAS_EXPIRED(): ErrorCode {
        return new ErrorCode('ATH18', 'RESTORE ACCOUNT TOKEN HAS EXPIRED', Response::HTTP_BAD_REQUEST);
    }

    public static function INVALID_EMAIL_VERIFIFICATION_TOKEN(): ErrorCode {
        return new ErrorCode('ATH19', 'INVALID EMAIL VERIFIFICATION TOKEN', Response::HTTP_BAD_REQUEST);
    }

    public static function EMAIL_VERIFIFICATION_TOKEN_HAS_EXPIRED(): ErrorCode {
        return new ErrorCode('ATH20', 'EMAIL VERIFIFICATION TOKEN HAS EXPIRED', Response::HTTP_BAD_REQUEST);
    }

    public static function MISSING_USER_INFORMATION(): ErrorCode {
        return new ErrorCode('ATH21', 'MISSING USER INFORMATION', Response::HTTP_OK);
    }

    public static function WAIT_BEFORE_RETRYING(): ErrorCode {
        return new ErrorCode('ATH22', 'WAIT BEFORE RETRYING', Response::HTTP_FORBIDDEN);
    }

    public static function WAIT_BEFORE_CHANGING_NAME(): ErrorCode {
        return new ErrorCode('ATH23', 'WAIT BEFORE CHANGING NAME', Response::HTTP_FORBIDDEN);
    }
}
