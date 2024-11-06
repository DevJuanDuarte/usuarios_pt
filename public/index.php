<?php

require '../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\PostController;
use App\Helpers\Response;

// Configuración de CORS
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Verifica si la solicitud es de tipo OPTIONS y responde con un estado 200
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];

$baseUri = '/usuarios_pt/public';
$requestUri = str_replace($baseUri, '', $requestUri);

$authController = new AuthController();
$postController = new PostController();

if ($method === 'POST' && $requestUri === '/api/register') {
    $data = json_decode(file_get_contents("php://input"), true);
    echo $authController->register($data);
} elseif ($method === 'POST' && $requestUri === '/api/login') {
    $data = json_decode(file_get_contents("php://input"), true);
    echo $authController->login($data);
} elseif ($method === 'GET' && $requestUri === '/api/users') {
    echo $authController->listUsers();
} elseif ($method === 'POST' && $requestUri === '/api/posts') {
    $data = json_decode(file_get_contents("php://input"), true);
    echo $postController->createPost($data);
} elseif ($method === 'GET' && preg_match('/\/api\/post\/(\d+)/', $requestUri, $matches)) {
    $id = $matches[1];
    echo $postController->getPostById($id);
} elseif ($method === 'GET' && $requestUri === '/api/posts') {
    echo $postController->listAllPosts();
} else {
    echo Response::json(404, "Endpoint no encontrado.");
}
