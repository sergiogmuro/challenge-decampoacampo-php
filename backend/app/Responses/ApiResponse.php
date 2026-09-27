<?php

namespace App\Responses;

class ApiResponse
{
    public static function make(mixed $response, int $code = 200): array
    {
        if ($code >= 400) {
            return [
                'error' => [
                    'code' => $code,
                    'message' => $response,
                ]
            ];
        }

        return [
            'data' => $response,
        ];
    }
}
