<?php

namespace App\Exceptions;

use App\Http\ErrorCodes\ErrorCode;
use Exception;

/**
 * Klasa przechowująca strukturę zwracanej odpowiedzi
 */
class ApiException extends Exception
{
    private ErrorCode $errorCode;
    private $data;
    private $metadata;

    /**
     * Ustawienie obiektu błędu, danych oraz metadanych
     * 
     * @param App\Http\ErrorCodes\ErrorCode $errorCode obiekt zwracanego błędu
     * @param $data podstawowe zwracane informacje
     * @param $metadata dodatkowe informacje
     */
    public function __construct(ErrorCode $errorCode, $data = null, $metadata = null) {

        parent::__construct();

        $this->errorCode = $errorCode;
        $this->data = $data;
        $this->metadata = $metadata;
    }

    public function getErrorCode(): ErrorCode {
        return $this->errorCode;
    }

    public function getData() {
        return $this->data;
    }

    public function getMetadata() {
        return $this->metadata;
    }
}
