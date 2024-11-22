<?php

namespace App\Config; // Define el espacio de nombres para la clase Database

use PDO; // Importa la clase PDO para la conexión a la base de datos
use PDOException; // Importa la clase PDOException para manejar errores de conexión

class Database {
    private $host = 'localhost'; // Define el host de la base de datos
    private $db_name = 'usuariosdb'; // Define el nombre de la base de datos
    private $username = 'root'; // Define el nombre de usuario para la conexión a la base de datos
    private $password = ''; // Define la contraseña para la conexión a la base de datos
    private $conn; // Variable para almacenar la conexión a la base de datos

    // Método para conectar a la base de datos
    public function connect() {
        $this->conn = null; // Inicializa la variable de conexión como null

        try {
            // Crea una nueva conexión PDO a la base de datos
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Configura el modo de error de PDO para lanzar excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // echo "Conexión establecida correctamente."; // Mensaje opcional para indicar que la conexión fue exitosa
            // var_dump($this->conn); // Muestra el objeto de conexión para depuración
        } catch (PDOException $e) {
            // Maneja los errores de conexión
            echo "Error de conexión: " . $e->getMessage();
        }

        return $this->conn; // Devuelve la conexión a la base de datos
    }
}
