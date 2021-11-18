<?php

namespace App\Http\ErrorCode;

class ErrorCode {

    private string $code;
    private string $codeMessage;
    private int $httpStatus;

    public function __construct(string $code, string $codeMessage, int $httpStatus) {
        $this->code = $code;
        $this->codeMessage = $codeMessage;
        $this->httpStatus = $httpStatus;
    }

    public function getCode(): string {
        return $this->code;
    }

    public function getCodeMessage(): string {
        return $this->codeMessage;
    }

    public function getHttpStatus(): int {
        return $this->httpStatus;
    }

}