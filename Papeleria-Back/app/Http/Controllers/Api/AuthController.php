<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // POST /api/registro (Creación de cuenta de cliente)
    public function register(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string',
            'email' => 'required|string|unique:usuarios,email',
            'password' => 'required|string|confirmed'
        ]);

        $usuario = Usuario::create([
            'nombre' => $fields['nombre'],
            'email' => $fields['email'],
            'password' => Hash::make($fields['password']),
            'rol' => 'usuario' // Siempre 'usuario' por defecto en el registro público
        ]);

        $token = $usuario->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token' => $token
        ], 201);
    }

    // POST /api/login (Inicio de sesión)
    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // 1. Verificar Email
        $usuario = Usuario::where('email', $fields['email'])->first();

        // 2. Verificar Contraseña
        if (!$usuario || !Hash::check($fields['password'], $usuario->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $usuario->createToken('myapptoken')->plainTextToken;

        return response()->json([
            'usuario' => $usuario,
            'token' => $token
        ], 200);
    }
    
    // POST /api/logout (Cerrar sesión)
    public function logout(Request $request)
    {
        // Elimina el token actual del usuario
        $request->user()->currentAccessToken()->delete(); 

        return response()->json(['message' => 'Sesión cerrada exitosamente']);
    }
}