<?php

require '../vendor/autoload.php'; // Autocarga las dependencias de Composer.

use App\Controllers\AuthController; // Importa la clase AuthController.
use App\Controllers\PostController; // Importa la clase PostController.
use App\Helpers\Response; // Importa la clase Response.

// Configuración de CORS
header("Access-Control-Allow-Origin: http://localhost:4200"); // Permite solicitudes desde el origen http://localhost:4200.
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Permite estos métodos HTTP.
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Permite estos encabezados HTTP.

// Verifica si la solicitud es de tipo OPTIONS y responde con un estado 200.
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // Responde con un código de estado 200.
    exit(); // Termina la ejecución del script.
}

header("Content-Type: application/json"); // Establece el tipo de contenido de la respuesta como JSON.

$method = $_SERVER['REQUEST_METHOD']; // Obtiene el método HTTP de la solicitud.
$requestUri = $_SERVER['REQUEST_URI']; // Obtiene la URI de la solicitud.

$baseUri = '/usuarios_pt/public'; // Define la base URI.
$requestUri = str_replace($baseUri, '', $requestUri); // Elimina la base URI de la URI de la solicitud.

$authController = new AuthController(); // Crea una nueva instancia del AuthController.
$postController = new PostController(); // Crea una nueva instancia del PostController.

if ($method === 'POST' && $requestUri === '/api/register') {
    // Maneja la solicitud de registro.
    $data = json_decode(file_get_contents("php://input"), true); // Decodifica los datos JSON de la solicitud.
    echo json_encode($authController->register($data)); // Llama al método register del AuthController y devuelve la respuesta en formato JSON.
} elseif ($method === 'POST' && $requestUri === '/api/login') {
    // Maneja la solicitud de inicio de sesión.
    $data = json_decode(file_get_contents("php://input"), true); // Decodifica los datos JSON de la solicitud.
    echo json_encode($authController->login($data)); // Llama al método login del AuthController y devuelve la respuesta en formato JSON.
} elseif ($method === 'GET' && $requestUri === '/api/users') {
    // Maneja la solicitud para listar usuarios.
    echo json_encode($authController->listUsers()); // Llama al método listUsers del AuthController y devuelve la respuesta en formato JSON.
} elseif ($method === 'POST' && $requestUri === '/api/posts') {
    // Maneja la solicitud para crear un nuevo post.
    $data = json_decode(file_get_contents("php://input"), true); // Decodifica los datos JSON de la solicitud.
    echo json_encode($postController->createPost($data)); // Llama al método createPost del PostController y devuelve la respuesta en formato JSON.
} elseif ($method === 'GET' && preg_match('/\/api\/post\/(\d+)/', $requestUri, $matches)) {
    // Maneja la solicitud para obtener un post por ID.
    $id = $matches[1]; // Extrae el ID del post de la URI.
    echo json_encode($postController->getPostById($id)); // Llama al método getPostById del PostController y devuelve la respuesta en formato JSON.
} elseif ($method === 'GET' && $requestUri === '/api/posts') {
    // Maneja la solicitud para listar todos los posts.
    echo json_encode($postController->listAllPosts()); // Llama al método listAllPosts del PostController y devuelve la respuesta en formato JSON.
} else {
    // Maneja las solicitudes a endpoints no encontrados.
    echo json_encode(Response::json(404, "Endpoint no encontrado.")); // Devuelve una respuesta JSON con código 404.
}
