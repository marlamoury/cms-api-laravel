<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserRequest;
use App\Traits\ApiResponseTrait;

class UserController extends Controller
{
    use ApiResponseTrait;

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Lista todos os usuários",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuários"
     *     )
     * )
     */
    public function index()
    {
        return $this->success(User::all());
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     summary="Cadastra um novo usuário",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "email", "password", "telefone"},
     *             @OA\Property(property="nome", type="string", example="Joana Lima"),
     *             @OA\Property(property="email", type="string", example="joana@example.com"),
     *             @OA\Property(property="password", type="string", example="senha123"),
     *             @OA\Property(property="telefone", type="string", example="(11) 91234-5678"),
     *             @OA\Property(property="is_valid", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário criado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação"
     *     )
     * )
     */
    public function store(UserRequest $request)
    {
        $user = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefone' => $request->telefone,
            'is_valid' => $request->is_valid ?? true
        ]);

        return $this->success($user, 'Usuário criado com sucesso.', 201);
    }

    public function show($id)
    {
        return $this->success(User::findOrFail($id));
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Atualiza um usuário",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nome", type="string", example="Joana Lima"),
     *             @OA\Property(property="email", type="string", example="joana@example.com"),
     *             @OA\Property(property="password", type="string", example="novaSenha456"),
     *             @OA\Property(property="telefone", type="string", example="(11) 91234-5678"),
     *             @OA\Property(property="is_valid", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário atualizado com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function update(UserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = [
            'name' => $request->nome,
            'email' => $request->email,
            'telefone' => $request->telefone,
            'is_valid' => $request->is_valid,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return $this->success($user, 'Usuário atualizado com sucesso.');
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     summary="Remove um usuário",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID do usuário",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuário removido com sucesso"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Usuário não encontrado"
     *     )
     * )
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return $this->success(null, 'Usuário removido com sucesso.');
    }
}
