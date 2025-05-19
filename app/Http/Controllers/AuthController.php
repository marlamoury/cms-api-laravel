<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * @OA\Info(
 *     title="CMS API",
 *     version="1.0.0",
 *     description="Documentação da API CMS"
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Servidor local"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     in="header",
 *     name="Authorization"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     summary="Registrar novo usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nome", "email", "password", "telefone"},
     *             @OA\Property(property="nome", type="string", example="Ana Costa"),
     *             @OA\Property(property="email", type="string", example="ana@example.com"),
     *             @OA\Property(property="password", type="string", example="senha123"),
     *             @OA\Property(property="telefone", type="string", example="(41) 99877-6655")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuário registrado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário registrado com sucesso."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="Ana Costa"),
     *                 @OA\Property(property="email", type="string", example="ana@example.com"),
     *                 @OA\Property(property="telefone", type="string", example="(41) 99877-6655"),
     *                 @OA\Property(property="is_valid", type="boolean", example=true)
     *             ),
     *             @OA\Property(property="token", type="string", example="seu-token-jwt-aqui")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao cadastrar o usuário."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'telefone' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Erro ao cadastrar o usuário. Verifique os dados fornecidos.',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'telefone' => $request->telefone,
            'is_valid' => true
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Usuário registrado com sucesso.',
            'user' => [
                'id' => $user->id,
                'nome' => $user->name,
                'email' => $user->email,
                'telefone' => $user->telefone,
                'is_valid' => $user->is_valid,
            ],
            'token' => $token
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     summary="Autentica um usuário",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="joao@example.com"),
     *             @OA\Property(property="password", type="string", example="senha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Login realizado com sucesso."),
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nome", type="string", example="João Silva"),
     *                 @OA\Property(property="email", type="string", example="joao@example.com"),
     *                 @OA\Property(property="telefone", type="string", example="(11) 98765-4321"),
     *                 @OA\Property(property="is_valid", type="boolean", example=true)
     *             ),
     *             @OA\Property(property="token", type="string", example="seu-token-jwt-aqui")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'message' => 'Credenciais inválidas. Verifique seu email e senha.'
            ], 401);
        }

        $user = JWTAuth::user();

        return response()->json([
            'message' => 'Login realizado com sucesso.',
            'user' => [
                'id' => $user->id,
                'nome' => $user->name,
                'email' => $user->email,
                'telefone' => $user->telefone,
                'is_valid' => $user->is_valid,
            ],
            'token' => $token
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     summary="Faz logout do usuário",
     *     tags={"Auth"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logout realizado com sucesso.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao realizar logout"
     *     )
     * )
     */
    public function logout()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Logout realizado com sucesso.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao realizar logout.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
