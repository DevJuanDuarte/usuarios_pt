<?php

namespace App\Controllers; // Define el espacio de nombres para la clase AuthController

use App\Models\User; // Importa la clase User del espacio de nombres Models
use App\Helpers\Response; // Importa la clase Response del espacio de nombres Helpers

class AuthController
{
    // Método para listar todos los usuarios
    public function listUsers()
    {
        $user = new User(); // Crea una nueva instancia del modelo User
        $users = $user->getAllUsers(); // Llama al método getAllUsers del modelo para obtener todos los usuarios

        // Devuelve una respuesta JSON con código 200 y la lista de usuarios
        return Response::json(200, "Lista de usuarios", $users);
    }

    // Método para registrar un nuevo usuario
    public function register($request)
    {
        // Verifica que los datos necesarios estén presentes
        if (!isset($request['name'], $request['email'], $request['password'])) {
            // Establece el código de estado HTTP 400 para datos incompletos
            http_response_code(400);
            return Response::json(400, "Datos incompletos para el registro de usuario."); // Devuelve una respuesta JSON con código 400
        }

        $user = new User(); // Crea una nueva instancia del modelo User
        $result = $user->register($request['name'], $request['email'], $request['password']); // Llama al método register del modelo para registrar un nuevo usuario

        if ($result === false) {
            // Establece el código de estado HTTP 400 si el correo ya está registrado
            http_response_code(400);
            return Response::json(400, "El correo electrónico ya está registrado."); // Devuelve una respuesta JSON con código 400
        }

        // Establece el código de estado HTTP 201 para éxito
        http_response_code(201);
        return Response::json(201, "Usuario registrado exitosamente.", $result); // Devuelve una respuesta JSON con código 201 y los datos del usuario registrado
    }

    // Método para iniciar sesión
    public function login($request)
    {
        // Verifica que los datos necesarios estén presentes
        if (!isset($request['email'], $request['password'])) {
            return Response::json(400, "Datos incompletos para el inicio de sesión."); // Devuelve una respuesta JSON con código 400
        }

        $user = new User(); // Crea una nueva instancia del modelo User
        $result = $user->login($request['email'], $request['password']); // Llama al método login del modelo para autenticar al usuario

        if (!$result) {
            return Response::json(401, "Credenciales incorrectas.", $result); // Devuelve una respuesta JSON con código 401 si las credenciales son incorrectas
        }

        // Devuelve una respuesta JSON con código 200 y los datos del usuario autenticado
        return Response::json(200, "Autenticación exitosa.", ['user' => $result]);
    }
}
