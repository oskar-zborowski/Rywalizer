<?php

namespace App\Http\Libraries\Http;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse
{
    public static function sendSuccess(array $data = null, array $metadata = null) {

        header('Content-Type: application/json');
        http_response_code(Response::HTTP_OK);

        echo json_encode([
            'data' => JsonResponse::convertToCamelCase($data),
            'metadata' => JsonResponse::convertToCamelCase($metadata)
        ]);

        die;
    }

    public static function sendError(string $errorCode, int $status, array $data = null, array $metadata = null) {

        header('Content-Type: application/json');
        http_response_code($status);

        echo json_encode([
            'errorCode' => $errorCode,
            'data' => JsonResponse::convertToCamelCase($data),
            'metadata' => JsonResponse::convertToCamelCase($metadata)
        ]);

        die;
    }

    public static function setCookie(string $value, string $name = 'JWT') {
        $expires = time()+env('COOKIE_LIFETIME')*60;
        setcookie($name, $value, $expires); // TODO Zastanowić się nad $secure (raczej powinno być na true) oraz $httponly
    }

    public static function deleteCookie(string $name = 'JWT') {
        setcookie($name, null, -1); // TODO Zastanowić się nad $secure (raczej powinno być na true) oraz $httponly
    }

    public static function convertToCamelCase(array $data = null, int $from = 0, int $to = null, int $current = 0) {

        $fieldNames = null;

        if ($data && (isset($to) && $from <= $to || !isset($to))) {
            $data = json_encode($data);
            $data = json_decode($data, true);

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (isset($to)) {
                        if ($current >= $from && $current <= $to) {
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);
                        } else if ($current < $from) {
                            $temp = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);

                            foreach ($temp as $k => $v) {
                                $fieldNames[Str::camel($k)] = $v;
                            }
                        }
                    } else {
                        if ($current < $from) {
                            $temp = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);

                            foreach ($temp as $k => $v) {
                                $fieldNames[Str::camel($k)] = $v;
                            }
                        } else {
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, $current+1);
                        }
                    }
                } else {
                    if ($current >= $from) {
                        if (isset($to) && $current <= $to || !isset($to)) {
                            $fieldNames[Str::camel($key)] = $value;
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