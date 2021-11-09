<?php

namespace App\Http\Libraries\Http;

use Illuminate\Support\Str;

class JsonRequest
{
    public static function convertToSnakeCase(array $data = null, int $from = 0, int $to = null, int $current = 0) {

        $fieldNames = null;

        if ($data && (isset($to) && $from <= $to || !isset($to))) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (isset($to)) {
                        if ($current >= $from && $current <= $to) {
                            $fieldNames[Str::snake($key)] = JsonRequest::convertToSnakeCase($value, $from, $to, $current+1);
                        } else if ($current < $from) {
                            $deep = JsonRequest::convertToSnakeCase($value, $from, $to, $current+1);

                            foreach ($deep as $k => $v) {
                                $fieldNames[Str::snake($k)] = $v;
                            }
                        }
                    } else {
                        if ($current < $from) {
                            $deep = JsonRequest::convertToSnakeCase($value, $from, $to, $current+1);

                            foreach ($deep as $k => $v) {
                                $fieldNames[Str::snake($k)] = $v;
                            }
                        } else {
                            $fieldNames[Str::snake($key)] = JsonRequest::convertToSnakeCase($value, $from, $to, $current+1);
                        }
                    }
                } else {
                    if ($current >= $from) {
                        if (isset($to) && $current <= $to || !isset($to)) {
                            $fieldNames[Str::snake($key)] = $value;
                        } else {
                            $fieldNames = null;
                        }
                    } else {
                        $fieldNames[] = chr(27);
                    }
                }
            }
        }

        if ($current == 0 && $fieldNames != null) {
            $fN = null;

            foreach ($fieldNames as $key => $value) {
                if ($value != chr(27)) {
                    $fN[$key] = $value;
                }
            }

            $fieldNames = $fN;
        }

        return $fieldNames;
    }
}