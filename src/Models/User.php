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

    public function exists($email)
    {
        $query = "SELECT COUNT(*) FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        return $stmt->fetchColumn() > 0;
    }

    public function register($name, $email, $password)
    {
        if ($this->exists($email)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);

        if ($stmt->execute()) {
            $userId = $this->conn->lastInsertId();
            $stmt = $this->conn->prepare("SELECT id, name, email FROM users WHERE id = :id");
            $stmt->bindParam(':id', $userId);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve los datos del usuario
        }

        return false;
    }

    public function login($email, $password)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $token = md5($user['id'] . time() . uniqid());
            $user['token'] = $token;

            return $user;
        }

        return false;
    }
}
