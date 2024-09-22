<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendEmailJob;

class AuthController extends Controller
{
    // 1. Registrar novos usuários (POST /api/v1/register)
    public function register(Request $request)
    {
        $messages = [
            'name.required' => 'O nome é obrigatório.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.unique' => 'O e-mail informado já está em uso.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $password = Str::random(10);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($password),
            ]);

            if (!$user) {
                return response()->json(['message' => 'Falha ao criar o usuário.'], 500);
            }

            dispatch(new SendEmailJob($user, 'Bem-vindo! Aqui está sua senha de acesso', 'Sua senha de acesso é: ' . $password));

            $token = JWTAuth::fromUser($user);

            return response()->json(['user' => $user, 'token' => $token], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao registrar usuário: ' . $e->getMessage()], 500);
        }
    }

    // 2. Fazer login (POST /api/v1/login)
    public function login(Request $request)
    {
        $messages = [
            'email.required' => 'O e-mail é obrigatório.',
            'password.required' => 'A senha é obrigatória.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $credentials = $request->only('email', 'password');

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Credenciais inválidas'], 401);
            }

            return response()->json(['token' => $token]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao realizar login: ' . $e->getMessage()], 500);
        }
    }

    // 3. Retorna o usuário autenticado (GET /api/v1/me)
    public function me()
    {
        try {
            return response()->json(auth()->user());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar o usuário: ' . $e->getMessage()], 500);
        }
    }

    // 4. Logout do usuário (POST /api/v1/logout)
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Logout realizado com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao realizar logout: ' . $e->getMessage()], 500);
        }
    }

    // 5. Listar todos os usuários com paginação (GET /api/v1/users)
    public function index(Request $request)
    {
        try {
            $perPage = $request->get('per_page', 10);
            $users = User::paginate($perPage);

            return response()->json($users);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao listar usuários: ' . $e->getMessage()], 500);
        }
    }

    // 6. Exibir detalhes de um usuário específico (GET /api/v1/users/{id})
    public function show($id)
    {
        try {
            $user = User::findOrFail($id);
            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao buscar usuário: ' . $e->getMessage()], 404);
        }
    }

    // 7. Atualizar um usuário existente (PUT /api/v1/users/{id})
    public function update(Request $request, $id)
    {
        $messages = [
            'name.required' => 'O nome é obrigatório.',
        ];

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::findOrFail($id);

            // Atualiza apenas o nome do usuário
            $user->update([
                'name' => $request->name,
            ]);

            return response()->json($user);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao atualizar usuário: ' . $e->getMessage()], 500);
        }
    }

    // 8. Excluir um usuário (DELETE /api/v1/users/{id})
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['message' => 'Usuário excluído com sucesso']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao excluir usuário: ' . $e->getMessage()], 500);
        }
    }

    // 9. Redefinir a senha do usuário e enviar nova por e-mail (POST /api/v1/users/{id}/reset-password)
    public function resetPassword(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            JWTAuth::invalidate(JWTAuth::fromUser($user));

            $newPassword = Str::random(10);
            $user->password = Hash::make($newPassword);
            $user->save();

            dispatch(new SendEmailJob($user, 'Redefinição de senha', 'Sua nova senha é: ' . $newPassword));

            return response()->json(['message' => 'Senha redefinida com sucesso e enviada por e-mail']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao redefinir senha: ' . $e->getMessage()], 500);
        }
    }
}
