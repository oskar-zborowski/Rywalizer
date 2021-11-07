<?php

namespace App\Http\Libraries\Http;

use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class JsonResponse
{
    public static function sendSuccess(array $data = null, array $metadata = null) {

        header('Content-Type: application/json');
        http_response_code(Response::HTTP_OK);

        // $data = null;
        // $data['a_b']['c_d']['e_f']['g_h']['i_j'] = 'jakis_tekst';

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
        setcookie($name, $value, $expires);
    }

    public static function deleteCookie(string $name = 'JWT') {
        setcookie($name, null, -1);
    }

    public static function convertToCamelCase(array $data = null, int $from = 0, int $to = null, int $current = 0) {

        $fieldNames = null;

        if ($data) {
            $data = json_encode($data);
            $data = json_decode($data, true);

            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    if (isset($to)) {
                        if ($current >= $from && $current <= $to) {
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, ++$current);
                        } else if ($current < $from) {
                            // TODO Tutaj dołożyć niezbędną logikę
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, ++$current);
                        }
                    } else {
                        if ($current < $from) {
                            // TODO Tutaj dołożyć niezbędną logikę
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, ++$current);
                        } else {
                            $fieldNames[Str::camel($key)] = JsonResponse::convertToCamelCase($value, $from, $to, ++$current);
                        }
                    }
                } else {
                    $fieldNames[Str::camel($key)] = $value;
                }
            }
        }

        return $fieldNames;
    }
}