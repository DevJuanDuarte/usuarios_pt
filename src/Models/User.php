<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class User
{
  private $conn;
  private $table = 'users';

  public function __construct()
  {
    $database = new Database();
    $this->conn = $database->connect();
  }

  public function getAllUsers()
  {
    $query = "SELECT id, name, email, created_at, updated_at FROM " . $this->table;
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }



  // En el modelo User
  public function register($name, $email, $password)
  {
    // Aquí agregarías la lógica para insertar el usuario en la base de datos
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Suponiendo que tienes un método para insertar el usuario en la base de datos
    $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
      // Recuperar los datos del usuario recién creado
      $userId = $this->conn->lastInsertId();
      $stmt = $this->conn->prepare("SELECT id, name, email FROM users WHERE id = :id");
      $stmt->bindParam(':id', $userId);
      $stmt->execute();

      // Aquí, usamos PDO::FETCH_ASSOC para obtener un array asociativo
      return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve los datos del usuario
    }

    return false; // En caso de error, se devuelve false
  }




  public function login($email, $password)
  {
    $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && password_verify($password, $user['password'])) {
      // Generar un token simple
      $token = md5($user['id'] . time() . uniqid()); // Usar una combinación única de ID, tiempo y un identificador único

      // Añadir el token al usuario
      $user['token'] = $token;

      return $user;
    }

    return false;
  }

}
