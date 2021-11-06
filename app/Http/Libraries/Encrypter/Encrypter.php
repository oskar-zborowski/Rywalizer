<?php

namespace App\Http\Libraries\Encrypter;

use Illuminate\Support\Facades\Hash;

class Encrypter
{
    private function fillWithRandomCharacters(string $text, int $maxSize, bool $rand = false) {

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

    private function removeRandomCharacters(string $text) {

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

    public function encrypt(string $text = null, int $maxSize = null) {
        $text = $this->fillWithRandomCharacters($text, $maxSize);
        return openssl_encrypt($text, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
    }

    public function decrypt(string $cipher = null) {
        $text = openssl_decrypt($cipher, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
        return $this->removeRandomCharacters($text);
    }

    public function hash(string $text) {
        return Hash::make($text);
    }

    public function generatePlainToken(int $maxSize = 32) {
        $maxSize = floor($maxSize * 0.75);
        $modulo = $maxSize % 3;
        $size = $maxSize - $modulo;
        return $this->fillWithRandomCharacters('', $size, true);
    }

    public function encryptToken(string $plainToken) {
        return $this->encrypt($plainToken);
    }
}
