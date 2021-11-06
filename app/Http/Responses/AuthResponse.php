<?php

namespace App\Http\Responses;

class AuthResponse
{
    public const UNAUTHORIZED = 'ATH1';
    public const INVALID_CREDENTIALS = 'ATH2';
    public const UNVERIFIED_EMAIL = 'ATH3';
    public const ACOUNT_DELETED = 'ATH4';
    public const ACOUNT_BLOCKED = 'ATH5';
    public const ALREADY_LOGGED_IN = 'ATH6';
    public const EMAIL_ALREADY_VERIFIED = 'ATH7';
    public const INVALID_PASSWORD_RESET_TOKEN = 'ATH8';
}
