<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Repositories\PostRepository;
use App\Traits\ApiResponseTrait;

class PostController extends Controller
{
    use ApiResponseTrait;

    protected $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
 * @OA\Get(
 *     path="/api/posts",
 *     summary="Lista todas as postagens",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="tag",
 *         in="query",
 *         description="Filtrar por tag",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="query",
 *         in="query",
 *         description="Buscar por título ou conteúdo",
 *         required=false,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Lista de posts"
 *     )
 * )
 */
    public function index(Request $request)
    {
        $posts = $this->postRepository->getAll($request);
        return $this->success($posts);
    }

    /**
 * @OA\Post(
 *     path="/api/posts",
 *     summary="Cria uma nova postagem",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "content", "user_id"},
 *             @OA\Property(property="title", type="string", example="Postagem de Exemplo"),
 *             @OA\Property(property="content", type="string", example="Conteúdo da postagem..."),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(
 *                 property="tags",
 *                 type="array",
 *                 @OA\Items(type="integer"),
 *                 example={1,2}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Post criado com sucesso"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação"
 *     )
 * )
 */

    public function store(PostRequest $request)
    {
        $post = $this->postRepository->create($request->validated());
        return $this->success($post, 'Post criado com sucesso.', 201);
    }

    public function show($id)
    {
        $post = $this->postRepository->find($id);
        return $this->success($post);
    }

    /**
 * @OA\Put(
 *     path="/api/posts/{id}",
 *     summary="Atualiza uma postagem",
 *     tags={"Posts"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID da postagem",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"title", "content", "user_id"},
 *             @OA\Property(property="title", type="string", example="Título atualizado"),
 *             @OA\Property(property="content", type="string", example="Conteúdo atualizado"),
 *             @OA\Property(property="user_id", type="integer", example=1),
 *             @OA\Property(property="tags", type="array", @OA\Items(type="integer"), example={1, 3})
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Post atualizado com sucesso"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Post não encontrado"
 *     )
 * )
 */
    public function update(PostRequest $request, $id)
    {
        $post = $this->postRepository->update($id, $request->validated());
        return $this->success($post, 'Post atualizado com sucesso.');
    }

    /**
 * @OA\Delete(
 *     path="/api/posts/{id}",
 *     summary="Remove uma postagem",
 *     tags={"Posts"},
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
 *         description="Post deletado com sucesso"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Post não encontrado"
 *     )
 * )
 */
    public function destroy($id)
    {
        $this->postRepository->delete($id);
        return $this->success(null, 'Post deletado com sucesso.');
    }
}
