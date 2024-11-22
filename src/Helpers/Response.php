<?php

namespace App\Helpers; // Define el espacio de nombres para la clase Response

class Response
{
    // Método estático para generar una respuesta JSON
    public static function json($status, $message, $data = null)
    {
        // Crea un array asociativo con el estado y el mensaje
        $response = [
            'status' => $status, // Código de estado HTTP (e.g., 200, 400, 404)
            'message' => $message, // Mensaje asociado al estado (e.g., "Éxito", "Error")
        ];

        // Si se proporciona datos adicionales, los añade al array de respuesta
        if (!is_null($data)) {
            $response['data'] = $data; // Añade datos adicionales a la respuesta
        }

        // Devuelve el array de respuesta
        return $response;
    }
}
