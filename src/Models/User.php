<?php

namespace App\Models; // Define el espacio de nombres para la clase User

use App\Config\Database; // Importa la clase Database para la conexión a la base de datos
use PDO; // Importa la clase PDO para la manipulación de la base de datos

class User
{
    private $conn; // Variable para almacenar la conexión a la base de datos
    private $table = 'users'; // Nombre de la tabla en la base de datos

    public function __construct()
    {
        $database = new Database(); // Crea una nueva instancia de la clase Database
        $this->conn = $database->connect(); // Obtiene la conexión a la base de datos
    }

    // Método para obtener todos los usuarios
    public function getAllUsers()
    {
        // Consulta SQL para obtener todos los usuarios
        $query = "SELECT id, name, email, created_at, updated_at FROM " . $this->table;
        $stmt = $this->conn->prepare($query); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta SQL

        // Devuelve todos los resultados de la consulta como un array asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para verificar si un usuario existe por su email
    public function exists($email)
    {
        // Consulta SQL para verificar si un usuario existe por su email
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query); // Prepara la consulta SQL
        $stmt->bindParam(':email', $email); // Asocia el parámetro email a la consulta
        $stmt->execute(); // Ejecuta la consulta SQL

        // Devuelve verdadero si el usuario existe, falso en caso contrario
        return $stmt->fetchColumn() > 0;
    }

    // Método para registrar un nuevo usuario
    public function register($name, $email, $password)
    {
        if ($this->exists($email)) { // Verifica si el email ya está registrado
            return false; // Si el email ya existe, retorna falso
        }

        // Hashea la contraseña usando BCRYPT
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Consulta SQL para insertar un nuevo usuario
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query); // Prepara la consulta SQL
        $stmt->bindParam(':name', $name); // Asocia el parámetro name a la consulta
        $stmt->bindParam(':email', $email); // Asocia el parámetro email a la consulta
        $stmt->bindParam(':password', $hashedPassword); // Asocia la contraseña hasheada a la consulta

        if ($stmt->execute()) { // Si la inserción es exitosa
            $userId = $this->conn->lastInsertId(); // Obtiene el ID del usuario insertado
            // Consulta SQL para obtener los datos del nuevo usuario
            $stmt = $this->conn->prepare("SELECT id, name, email FROM users WHERE id = :id");
            $stmt->bindParam(':id', $userId); // Asocia el parámetro id a la consulta
            $stmt->execute(); // Ejecuta la consulta SQL

            // Devuelve los datos del usuario como un array asociativo
            return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve los datos del usuario
        }

        return false; // Si algo falla, retorna falso
    }

    // Método para iniciar sesión
    public function login($email, $password)
    {
        // Consulta SQL para obtener los datos del usuario por email
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query); // Prepara la consulta SQL
        $stmt->bindParam(':email', $email); // Asocia el parámetro email a la consulta
        $stmt->execute(); // Ejecuta la consulta SQL

        // Obtiene los datos del usuario como un array asociativo
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verifica la contraseña proporcionada con la contraseña hasheada
        if ($user && password_verify($password, $user['password'])) {
            // Genera un token único para el usuario
            $token = md5($user['id'] . time() . uniqid());
            $user['token'] = $token; // Asocia el token al usuario

            // Devuelve los datos del usuario, incluyendo el token
            return $user;
        }

        return false; // Si la autenticación falla, retorna falso
    }
}
