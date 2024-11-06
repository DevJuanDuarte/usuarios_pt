<?php

namespace App\Helpers;

class Response
{
    public static function json($status, $message, $data = null)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return $response;
    }
}
