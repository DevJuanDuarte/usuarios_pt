<?php

namespace App\Controllers;

use App\Models\User;
use App\Helpers\Response;

class AuthController
{

  public function listUsers()
  {
    $user = new User();
    $users = $user->getAllUsers();

    return Response::json(200, "Lista de usuarios", $users);
  }


  public function register($request)
  {
    // Verificar si los datos requeridos están presentes en $request
    if (!isset($request['name'], $request['email'], $request['password'])) {
      return Response::json(400, "Datos incompletos para el registro de usuario.");
    }

    $user = new User();
    $result = $user->register($request['name'], $request['email'], $request['password']);

    return $result ? Response::json(201, "Usuario registrado exitosamente.", $result) : Response::json(400, "Error al registrar usuario.", $result);
  }



  public function login($request)
  {
    $user = new User();
    $result = $user->login($request['email'], $request['password']);

    return $result ? Response::json(200, "Autenticación exitosa.", ['user' => $result]) : Response::json(401, "Credenciales incorrectas.");
  }
}
