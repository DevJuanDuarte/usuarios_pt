<?php

namespace App\Models;

use App\Config\Database;
use PDO;

class Post
{
    private $conn;
    private $table = 'posts';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->connect();
    }

    // Método para crear un nuevo post
    public function createPost($title, $content, $userid)
    {
        $query = "INSERT INTO " . $this->table . " (title, content, userid) VALUES (:title, :content, :userid)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':userid', $userid);

        if ($stmt->execute()) {
            // Devolver el post creado
            $postId = $this->conn->lastInsertId();
            return $this->getPostById($postId);
        }
        return false;
    }

    // Método para obtener un post por su ID
    public function getPostById($id)
    {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los posts
    public function getAllPosts()
    {
        $query = "
        SELECT posts.id, posts.title, posts.content, posts.created_at, users.name FROM " . $this->table . " AS posts
        LEFT JOIN users ON posts.userid = users.id
        ORDER BY posts.id DESC
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
