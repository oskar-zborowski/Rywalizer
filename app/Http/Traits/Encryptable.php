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
    private function encryptable(string $key): bool {
        return in_array($key, array_keys($this->encryptable));
    }

    /**
     * Odszyfrowanie pola, jeżeli jest taka możliwość
     * 
     * @param string $key nazwa pola do odszyfrowania
     * 
     * @return string|null
     */
    public function getAttribute($key): ?string {

        $value = parent::getAttribute($key);

        if ($this->encryptable($key)) {

            $value = null;

            if ($value) {
                $encrypter = new Encrypter;
                $value = $encrypter->decrypt((string) $value);
            }
        }

        return $value;
    }

    /**
     * Zaszyfrowanie pola, jeżeli jest taka możliwość
     * 
     * @param string $key nazwa pola do zaszyfrowania
     * @param string $value wartość do zaszyfrowania
     * 
     * @return string|null
     */
    public function setAttribute($key, $value): ?string {

        if ($this->encryptable($key)) {

            $value = null;

            if ($value) {
                $encrypter = new Encrypter;
                $value = $encrypter->encrypt((string) $value, (int) $this->encryptable[$key]);
            }
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * Odszyfrowanie całej grupy pól
     * 
     * @return array|null
     */
    public function getArrayableAttributes(): ?array {

        $attributes = parent::getArrayableAttributes();

        $encrypter = new Encrypter;

        foreach ($attributes as $key => $attribute) {
            if ($this->encryptable($key)) {

                $attributes[$key] = null;

                if ($attribute) {
                    $attributes[$key] = $encrypter->decrypt((string) $attribute);
                }
            }
        }

        return $attributes;
    }
}
