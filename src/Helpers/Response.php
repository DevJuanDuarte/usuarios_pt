<?php

namespace App\Helpers;

class Response {
    public static function json($status, $message, $data = null) {
        http_response_code($status);
        $response = [
            'status' => $status,
            'message' => $message,
            'data' => $data
        ];
        return json_encode($response);
    }
}
