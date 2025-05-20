<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Endpoints para gerenciar postagens"
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Listar todas as postagens",
     *     description="Retorna todas as postagens com os dados do autor.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de posts retornada com sucesso"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao buscar posts"
     *     )
     * )
     */
    public function index()
    {
        try {
            $posts = Post::with('user')->latest()->get();
            return response()->json($posts, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar posts'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     tags={"Posts"},
     *     summary="Criar uma nova postagem",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "content", "tags", "author"},
     *             @OA\Property(property="title", type="string", example="Título do post"),
     *             @OA\Property(property="content", type="string", example="Conteúdo da postagem"),
     *             @OA\Property(property="tags", type="array", @OA\Items(type="string"), example={"notícia", "geral"}),
     *             @OA\Property(property="author", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Dados inválidos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro interno ao criar post"
     *     )
     * )
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'tags' => 'array',
                'author' => 'required|exists:users,id',
            ]);

       $post = Post::create([
    'title' => $request->title,
    'content' => $request->content, // CORRIGIDO AQUI
    'tags' => $request->tags,
    'user_id' => $request->author,
]);


            return response()->json($post, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao criar post', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     tags={"Posts"},
     *     summary="Visualizar uma postagem específica",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID da postagem",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post retornado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post não encontrado"
     *     )
     * )
     */
    public function show($id)
    {
        try {
            $post = Post::with('user', 'comments.user')->findOrFail($id);
            return response()->json($post);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Post não encontrado'], 404);
        }
    }
}
