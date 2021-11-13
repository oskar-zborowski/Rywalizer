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
     * @param string $key nazwa pola do zaszyfrowania/odszyfrowania
     * 
     * @return bool
     */
    private function encryptable(?string $key): bool {
        return in_array($key, $this->encryptable);
    }

    /**
     * Szyfrowanie pola, jeżeli jest szyfrowalne
     * 
     * @param string $key nazwa pola do zaszyfrowania
     * 
     * @return string
     */
    public function getAttribute($key): ?string {
        
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
     * @param string $key nazwa pola do odszyfrowania
     * @param string $value wartość do odszyfrowania
     * 
     * @return string
     */
    public function setAttribute($key, $value): ?string {

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
