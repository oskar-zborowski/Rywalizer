<?php

namespace App\Http\Libraries\Encrypter;

use Illuminate\Support\Facades\Hash;

/**
 * Klasa umożliwiająca przeprowadzanie procesów szyfrowania danych
 */
class Encrypter
{
    /**
     * Wypełnienie tekstu losowymi znakami
     * 
     * @param string $text pole do wypełnienia losowymi znakami
     * @param int $maxSize maksymalny rozmiar pola
     * @param bool $rand flaga określająca czy dodawane znaki mają być losowe czy według kolejności
     * 
     * @return string
     */
    private function fillWithRandomCharacters(?string $text, int $maxSize = null, bool $rand = false): string {

        $characters = 'M9w4RimKrF8fJGuTEBpC36gUNDzebW7ZaVSnqdYcXhoQjILv21ltPkAHx5O0sy';
        $charactersLength = strlen($characters);

        if (!isset($maxSize)) {
            $maxSize = strlen($text);
        }

        $length = $maxSize - strlen($text);

        if ($length) {
            if (!$rand) {
                $text .= chr(27); // ESC

                for ($i=0; $i<$length-1; $i++) {
                    $text .= $characters[$i % $charactersLength];
                }
            } else {
                for ($i=0; $i<$length; $i++) {
                    $text .= $characters[rand(0, $charactersLength-1)];
                }
            }
        }

        return $text;
    }

    /**
     * Usunięcie losowych znaków z tekstu
     * 
     * @param string $text pole do odfiltrowania z losowych znaków
     * 
     * @return string
     */
    private function removeRandomCharacters(?string $text): string {

        $length = strlen($text);

        for ($i=0; $i<$length; $i++) {
            if (ord($text[$i]) == 27) {
                break;
            }
        }

        if ($i < $length) {
            $text = substr($text, 0, -($length-$i));
        }

        return $text;
    }

    /**
     * Zaszyfrowanie tekstu
     * 
     * @param string $text pole do zaszyfrowania
     * @param int $maxSize maksymalny rozmiar pola
     * 
     * @return string
     */
    public function encrypt(?string $text, int $maxSize = null): string {
        $text = $this->fillWithRandomCharacters($text, $maxSize);
        return openssl_encrypt($text, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
    }

    /**
     * Odszyfrowanie tekstu
     * 
     * @param string $cipher pole do odszyfrowania
     * 
     * @return string
     */
    public function decrypt(?string $cipher): string {
        $text = openssl_decrypt($cipher, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
        return $this->removeRandomCharacters($text);
    }

    /**
     * Zahashowanie tekstu
     * 
     * @param string $text pole do zahashowania
     * 
     * @return string
     */
    public function hash(string $text): string {
        return Hash::make($text);
    }

    /**
     * Generowanie tokenu
     * 
     * @param int $maxSize maksymalny rozmiar pola
     * 
     * @return string
     */
    public function generatePlainToken(int $maxSize = 32): string {
        $maxSize = floor($maxSize * 0.75);
        $modulo = $maxSize % 3;
        $size = $maxSize - $modulo;
        return $this->fillWithRandomCharacters('', $size, true);
    }

    /**
     * Szyfrowanie tokenu do przechowywania w bazie danych
     * 
     * @param string $plainToken token do zaszyfrowania
     * 
     * @return string
     */
    public function encryptToken(string $plainToken): string {
        return $this->encrypt($plainToken);
    }
}
