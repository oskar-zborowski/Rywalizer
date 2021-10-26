<?php

namespace App\Http\Libraries;

use Illuminate\Support\Facades\Hash;

class Encrypter {

    private function fillWithRandomCharacters($value, $maxSize = null) {

        $characters = 'M9w4RimKrF8fJGuTEBpC36gUNDzebW7ZaVSnqdYcXhoQjILv21ltPkAHx5O0sy';
        $charactersLength = strlen($characters);

        if (!$maxSize) {
            $maxSize = strlen($value);
        }

        $length = $maxSize - strlen($value);

        if ($length) {
            $value .= chr(27); // ESC

            for ($i=0; $i<$length-1; $i++) {
                $value .= $characters[$i % $charactersLength];
            }
        }

        return $value;
    }

    private function removeRandomCharacters($value) {

        $length = strlen($value);

        for ($i=0; $i<$length; $i++) {
            if (ord($value[$i]) == 27) {
                break;
            }
        }

        if ($i < $length) {
            $value = substr($value, 0, -($length-$i));
        }

        return $value;
    }

    public function encrypt($value, $maxSize = null) {
        $value = $this->fillWithRandomCharacters($value, $maxSize);
        return openssl_encrypt($value, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
    }

    public function decrypt($value) {
        $value = openssl_decrypt($value, env('OPENSSL_ALGORITHM'), env('OPENSSL_PASSPHRASE'), 0, env('OPENSSL_IV'));
        return $this->removeRandomCharacters($value);
    }

    public function hash($value) {
        return Hash::make($value);
    }
}