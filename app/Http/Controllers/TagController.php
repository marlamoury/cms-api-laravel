<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\TagRequest;
use App\Traits\ApiResponseTrait;

class TagController extends Controller
{
    use ApiResponseTrait;

    /**
 * @OA\Get(
 *     path="/api/tags",
 *     summary="Lista todas as tags",
 *     tags={"Tags"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *         response=200,
 *         description="Lista de tags"
 *     )
 * )
 */

    public function index()
    {
        return $this->success(Tag::all());
    }

    /**
 * @OA\Post(
 *     path="/api/tags",
 *     summary="Cria uma nova tag",
 *     tags={"Tags"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="laravel")
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tag criada com sucesso"
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Erro de validação"
 *     )
 * )
 */

    public function store(TagRequest $request)
    {
        $tag = Tag::create($request->validated());
        return $this->success($tag, 'Tag criada com sucesso.', 201);
    }

    public function show($id)
    {
        return $this->success(Tag::findOrFail($id));
    }

    /**
 * @OA\Put(
 *     path="/api/tags/{id}",
 *     summary="Atualiza uma tag existente",
 *     tags={"Tags"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID da tag",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name"},
 *             @OA\Property(property="name", type="string", example="framework")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tag atualizada com sucesso"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tag não encontrada"
 *     )
 * )
 */

    public function update(TagRequest $request, $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->update($request->validated());
        return $this->success($tag, 'Tag atualizada com sucesso.');
    }

    /**
 * @OA\Delete(
 *     path="/api/tags/{id}",
 *     summary="Remove uma tag",
 *     tags={"Tags"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID da tag",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tag removida com sucesso"
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tag não encontrada"
 *     )
 * )
 */

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();
        return $this->success(null, 'Tag removida com sucesso.');
    }
}
