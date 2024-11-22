<?php

namespace App\Controllers; // Define el espacio de nombres para la clase PostController

use App\Models\Post; // Importa la clase Post del espacio de nombres Models
use App\Helpers\Response; // Importa la clase Response del espacio de nombres Helpers

class PostController
{
    // Método para crear un nuevo post
    public function createPost($request)
    {
        // Verifica que los datos necesarios estén presentes y que el usuario esté logueado (userid != 0)
        if (!isset($request['title'], $request['content'], $request['userid']) || $request['userid'] == 0) {
            return Response::json(400, "Datos incompletos o el usuario no está logueado."); // Devuelve una respuesta JSON con código 400 si faltan datos o el usuario no está logueado
        }
    
        $post = new Post(); // Crea una nueva instancia del modelo Post
        $result = $post->createPost($request['title'], $request['content'], $request['userid']); // Llama al método createPost del modelo para crear un nuevo post
    
        // Devuelve una respuesta JSON con código 201 si el post se creó exitosamente, de lo contrario devuelve código 400
        return $result ? Response::json(201, "Post creado exitosamente.", $result) : Response::json(400, "Error al crear post.");
    }
    
    // Método para obtener un post por ID
    public function getPostById($id)
    {
        $post = new Post(); // Crea una nueva instancia del modelo Post
        $result = $post->getPostById($id); // Llama al método getPostById del modelo para obtener un post por su ID

        // Devuelve una respuesta JSON con código 200 si el post se encontró, de lo contrario devuelve código 404
        return $result ? Response::json(200, "Post encontrado.", $result) : Response::json(404, "Post no encontrado.");
    }

    // Método para listar todos los posts
    public function listAllPosts()
    {
        $post = new Post(); // Crea una nueva instancia del modelo Post
        $posts = $post->getAllPosts(); // Llama al método getAllPosts del modelo para obtener todos los posts

        // Devuelve una respuesta JSON con código 200 y la lista de posts
        return Response::json(200, "Lista de posts", $posts);
    }
}
