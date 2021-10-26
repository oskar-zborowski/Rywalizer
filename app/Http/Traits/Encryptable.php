<?php

namespace App\Http\Traits;

use App\Http\Libraries\Encrypter;

trait Encryptable
{
    private function encryptable($key) {
        return in_array($key, $this->encryptable);
    }

    public function getAttribute($key) {

        $value = parent::getAttribute($key);

        if ($this->encryptable($key)) {
            $encrypter = new Encrypter;
            $value = $encrypter->decrypt($value);
        }

        return $value;
    }

    public function setAttribute($key, $value) {

        if ($this->encryptable($key)) {
            $encrypter = new Encrypter;
            $value = $encrypter->encrypt($value, $this->maxSize[$key]);
        }

        return parent::setAttribute($key, $value);
    }

    public function getArrayableAttributes() {

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