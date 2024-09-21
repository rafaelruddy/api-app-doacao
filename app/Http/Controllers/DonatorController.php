<?php

namespace App\Http\Controllers;

use App\Http\Resources\DonatorResource;
use App\Models\Donator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;

class DonatorController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:donators',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|unique:donators',
            ], [
                'name.required' => 'O nome é obrigatório.',
                'name.string' => 'O nome deve ser uma string.',
                'name.max' => 'O nome não pode ter mais de :max caracteres.',
                'email.required' => 'O e-mail é obrigatório.',
                'email.string' => 'O e-mail deve ser uma string.',
                'email.email' => 'Informe um e-mail válido.',
                'email.max' => 'O e-mail não pode ter mais de :max caracteres.',
                'email.unique' => 'Este e-mail já está em uso.',
                'password.required' => 'A senha é obrigatória.',
                'password.string' => 'A senha deve ser uma string.',
                'password.min' => 'A senha deve ter pelo menos :min caracteres.',
                'phone.required' => 'O telefone é obrigatório.',
                'phone.unique' => 'Este telefone já foi cadastrado por outro usuário.',
            ]);

            $donator = Donator::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);

            $token = Auth::guard('donators')->attempt($request->only(['email', 'password']));
            if ($token) {
                $expiration = JWTAuth::factory()->getTTL();
                $expirationDateTime = Carbon::now()->addMinutes($expiration);
            }

            return response()->json([
                'message' => 'Conta criada com sucesso!',
                'token' => $token,
                'expiration_date' => $expirationDateTime,
                'data' => new DonatorResource($donator)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao processar o registro do usuário: ' . $e], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $token = Auth::guard('donators')->attempt($credentials);
            if ($token) {
                $donator = Auth::guard('donators')->user();
                $expiration = JWTAuth::factory()->getTTL();
                $expirationDateTime = Carbon::now()->addMinutes($expiration);

                return response()->json([
                    'message' => 'Login efetuado com sucesso!',
                    'token' => $token,
                    'expiration_date' => $expirationDateTime->toIso8601String(),
                    'data' => new DonatorResource($donator)
                ]);
            }

            return response()->json(['message' => 'Credenciais inválidas'], 401);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 401);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro interno do servidor: ' . $e], 500);
        }
    }

    public function loggedInfo()
    {
        try {
            $donator = Auth::guard('donators')->user();
            return response()->json(new DonatorResource($donator));
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao pegar informações do usuário: ' . $e], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $donator = Auth::guard('donators')->user();

            $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|string|email|max:255|unique:donators',
                'password' => 'sometimes|string|min:8',
                'phone' => 'sometimes|string|unique:donators',
            ]);

            $donator->update($request->except('password'));

            return response()->json([
                'message' => 'Informações do usuário atualizadas com sucesso!',
                'data' => new DonatorResource($donator),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao processar a atualização do usuário: ' . $e], 500);
        }
    }

    public function updatePassword(Request $request)
    {
        try {
            $donator = Auth::guard('donators')->user();

            $request->validate([
                'actual_password' => 'required|string',
                'password' => 'required|string|min:8'
            ]);

            if (!Hash::check($request->actual_password, $donator->password)) {
                return response()->json(['message' => 'Senha atual fornecida está incorreta.'], 400);
            }

            $donator->password = Hash::make($request->password);
            $donator->save();

            return response()->json(['message' => 'Senha atualizada com sucesso.'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Erro ao processar a atualização do usuário: ' . $e], 500);
        }
    }
}
