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
        setcookie($name, $value, $expires);
    }

    public static function deleteCookie(string $name = 'JWT') {
        setcookie($name, null, -1);
    }

    private static function convertToCamelCase(array $data = null) {

        $response = null;

        if ($data) {
            foreach ($data as $key => $value) {
                $response[Str::camel($key)] = $value;
            }
        }

        return $response;
    }
}