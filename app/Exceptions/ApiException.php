<?php

namespace App\Exceptions;

use Exception;
use App\Http\ErrorCodes\ErrorCode;

/**
 * Klasa przechowująca strukturę zwracanej odpowiedzi
 */
class ApiException extends Exception
{
    private ErrorCode $errorCode;
    private mixed $data;
    private mixed $metadata;

    /**
     * Ustawienie obiektu błędu, danych oraz metadanych
     * 
     * @param App\Http\ErrorCodes\ErrorCode $errorCode obiekt zwracanego błędu
     * @param mixed $data podstawowe zwracane informacje
     * @param mixed $metadata dodatkowe informacje
     */
    public function __construct(ErrorCode $errorCode, mixed $data = null, mixed $metadata = null) {
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
