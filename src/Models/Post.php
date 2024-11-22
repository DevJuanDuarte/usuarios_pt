<?php

namespace App\Models; // Define el espacio de nombres para la clase Post

use App\Config\Database; // Importa la clase Database para la conexión a la base de datos
use PDO; // Importa la clase PDO para la manipulación de la base de datos

class Post
{
    private $conn; // Variable para almacenar la conexión a la base de datos
    private $table = 'posts'; // Nombre de la tabla en la base de datos

    public function __construct()
    {
        $database = new Database(); // Crea una nueva instancia de la clase Database
        $this->conn = $database->connect(); // Obtiene la conexión a la base de datos
    }

    // Método para crear un nuevo post
    public function createPost($title, $content, $userid)
    {
        // Consulta SQL para insertar un nuevo post
        $query = "INSERT INTO " . $this->table . " (title, content, userid) VALUES (:title, :content, :userid)";
        $stmt = $this->conn->prepare($query); // Prepara la consulta SQL
        $stmt->bindParam(':title', $title); // Asocia el parámetro title a la consulta
        $stmt->bindParam(':content', $content); // Asocia el parámetro content a la consulta
        $stmt->bindParam(':userid', $userid); // Asocia el parámetro userid a la consulta

        if ($stmt->execute()) { // Si la inserción es exitosa
            $postId = $this->conn->lastInsertId(); // Obtiene el ID del post insertado
            return $this->getPostById($postId); // Devuelve los datos del post creado
        }
        return false; // Si algo falla, retorna falso
    }

    // Método para obtener un post por su ID
    public function getPostById($id)
    {
        // Consulta SQL para obtener un post por su ID
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query); // Prepara la consulta SQL
        $stmt->bindParam(':id', $id); // Asocia el parámetro id a la consulta
        $stmt->execute(); // Ejecuta la consulta SQL

        // Devuelve los datos del post como un array asociativo
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Método para obtener todos los posts
    public function getAllPosts()
    {
        // Consulta SQL para obtener todos los posts, junto con el nombre del usuario que los creó
        $query = "
        SELECT posts.id, posts.title, posts.content, posts.created_at, users.name FROM " . $this->table . " AS posts
        LEFT JOIN users ON posts.userid = users.id
        ORDER BY posts.id DESC
        ";
        $stmt = $this->conn->prepare($query); // Prepara la consulta SQL
        $stmt->execute(); // Ejecuta la consulta SQL

        // Devuelve todos los resultados de la consulta como un array asociativo
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
