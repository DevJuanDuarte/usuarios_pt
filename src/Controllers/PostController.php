<?php

namespace App\Controllers;

use App\Models\Post;
use App\Helpers\Response;

class PostController
{
    // Método para crear un nuevo post
    public function createPost($request)
    {
        if (!isset($request['title'], $request['content'], $request['userid'])) {
            return Response::json(400, "Datos incompletos para crear un post.");
        }

        $post = new Post();
        $result = $post->createPost($request['title'], $request['content'], $request['userid']);

        return $result ? Response::json(201, "Post creado exitosamente.", $result) : Response::json(400, "Error al crear post.");
    }

    // Método para obtener un post por ID
    public function getPostById($id)
    {
        $post = new Post();
        $result = $post->getPostById($id);

        return $result ? Response::json(200, "Post encontrado.", $result) : Response::json(404, "Post no encontrado.");
    }

    // Método para listar todos los posts
    public function listAllPosts()
    {
        $post = new Post();
        $posts = $post->getAllPosts();

        return Response::json(200, "Lista de posts", $posts);
    }
}
