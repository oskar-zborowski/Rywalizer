<?php

namespace App\Exceptions;

use Exception;
use App\Http\ErrorCode\ErrorCode;

class ApiException extends Exception {

    private ErrorCode $errorCode;
    private array $data;
    private array $metadata;

    public function __construct(ErrorCode $errorCode, array $data = [], array $metadata = []) {
        parent::__construct();

        $this->errorCode = $errorCode;
        $this->data = $data;
        $this->metadata = $metadata;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    public function getData() {
        return $this->data;
    }

    public function getMetadata() {
        return $this->metadata;
    }

}