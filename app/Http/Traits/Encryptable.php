<?php

namespace App\Http\Traits;

use App\Http\Libraries\Encrypter\Encrypter;

/**
 * Trait przeprowadzający proces szyfrowania i deszyfrowania pól w bazie danych
 */
trait Encryptable
{
    /**
     * Sprawdzenie czy dane pole powinno być szyfrowane
     * 
     * @param string $key
     * 
     * @return bool
     */
    private function encryptable($key): bool {
        return in_array($key, $this->encryptable);
    }

    /**
     * Szyfrowanie pola, jeżeli jest szyfrowalne
     * 
     * @param string $key
     * 
     * @return string
     */
    public function getAttribute($key) {
        
        $value = parent::getAttribute($key);

        if ($this->encryptable($key)) {
            $encrypter = new Encrypter;
            $value = $encrypter->decrypt($value);
        }

        return $value;
    }

    /**
     * Deszyfrowanie pola, jeżeli jest deszyfrowalne
     * 
     * @param string $key
     * @param string $value
     * 
     * @return string
     */
    public function setAttribute($key, $value) {

        if ($this->encryptable($key)) {
            $encrypter = new Encrypter;
            $value = $encrypter->encrypt($value, $this->maxSize[$key]);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Deszyfrowanie całej grupy pól
     * 
     * @return array
     */
    public function getArrayableAttributes(): array {

        $attributes = parent::getArrayableAttributes();

        foreach ($attributes as $key => $attribute) {
            if ($this->encryptable($key)) {
                $encrypter = new Encrypter;
                $attributes[$key] = $encrypter->decrypt($attribute);
            }
        }

        return $attributes;
    }
}
