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
    private function fillWithRandomCharacters(string $text = '', int $maxSize = null, bool $rand = false): string {

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
    private function removeRandomCharacters(string $text): string {

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
     * @param string|null $text pole do zaszyfrowania
     * @param int $maxSize maksymalny rozmiar pola
     * 
     * @return string|null
     */
    public function encrypt(?string $text, int $maxSize = null): ?string {

        if ($text !== null && strlen($text) > 0) {
            $text = $this->fillWithRandomCharacters($text, $maxSize);
            $text = openssl_encrypt($text, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
        }

        return $text;
    }

    /**
     * Odszyfrowanie tekstu
     * 
     * @param string|null $text pole do odszyfrowania
     * 
     * @return string|null
     */
    public function decrypt(?string $text): ?string {

        if ($text !== null && strlen($text) > 0) {
            $text = openssl_decrypt($text, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
            $text = $this->removeRandomCharacters($text);
        }

        return $text;
    }

    /**
     * Zahashowanie tekstu
     * 
     * @param string|null $text pole do zahashowania
     * 
     * @return string|null
     */
    public function hash(?string $text): ?string {

        if ($text !== null && strlen($text) > 0) {
            $text = Hash::make($text);
        }

        return $text;
    }

    /**
     * Generowanie tokenu
     * 
     * @param int $maxSize maksymalny rozmiar pola w bazie danych
     * @param string $addition dodatkowy tekst, który ma być uwzględniony przy generowaniu tokenu (dopisany na końcu)
     * 
     * @return string|null
     */
    public function generatePlainToken(int $maxSize = 32, string $addition = ''): ?string {

        $additionLength = strlen($addition);

        $maxSize = floor($maxSize * 0.75);
        $modulo = $maxSize % 3;
        $size = $maxSize - $modulo - $additionLength;

        $plainToken = null;

        if ($size >= 0) {
            $plainToken = $this->fillWithRandomCharacters('', $size, true) . $addition;
        }

        return $plainToken;
    }

    /**
     * Szyfrowanie tokenu do przechowywania w bazie danych
     * 
     * @param string|null $plainToken token do zaszyfrowania
     * 
     * @return string|null
     */
    public function encryptToken(?string $plainToken): ?string {

        $encryptedToken = null;

        if ($plainToken !== null && strlen($plainToken) > 0) {
            $encryptedToken = $this->encrypt($plainToken);
        }

        return $encryptedToken;
    }
}
