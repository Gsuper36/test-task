<?php

namespace App\Helpers;

class ApiResponseHelper
{
    public static function success(array $data = [])
    {

        $response = [
            'success' => true
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return self::response($response);
    }

    private static function response(array $response, int $status = 200, array $headers = [])
    {
        return response()->json(
            $response,
            $status,
            $headers,
            JSON_PRETTY_PRINT
        );
    }

    public static function error(array $data)
    {
        return self::response([
            'success' => false,
            'data'    => $data
        ]);
    }
}
