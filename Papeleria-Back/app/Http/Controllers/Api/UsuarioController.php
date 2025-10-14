<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    /**
     * [ADMIN] Muestra una lista de todos los usuarios (clientes).
     * GET /api/clientes (si se mapea a esta ruta)
     */
    public function index()
    {
        // Solo lista los usuarios con rol 'usuario' (clientes)
        $usuarios = Usuario::where('rol', 'usuario')->get();

        return response()->json($usuarios);
    }

    /**
     * [CLIENTE] Muestra el perfil del usuario autenticado.
     * GET /api/usuario
     */
    public function show(Request $request)
    {
        // $request->user() accede al modelo del usuario autenticado vía Sanctum
        $usuario = $request->user();

        // Nota: Laravel ya maneja el 401 si el token es inválido antes de llegar aquí.
        return response()->json($usuario);
    }

    /**
     * [CLIENTE] Actualiza la información del perfil del usuario autenticado.
     * PUT /api/usuario
     */
    public function update(Request $request)
    {
        $usuario = $request->user();

        // Reglas de validación: El email debe ser único, excluyendo el email actual del usuario
        $request->validate([
            'nombre' => 'sometimes|required|string|max:100',
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('usuarios')->ignore($usuario->id), // Ignora el email actual
            ],
            'password' => 'nullable|string|min:6|confirmed', // 'confirmed' busca un campo 'password_confirmation'
        ]);

        // Actualizar datos básicos (nombre y email)
        $usuario->nombre = $request->input('nombre', $usuario->nombre);
        $usuario->email = $request->input('email', $usuario->email);

        // Si se proporciona una nueva contraseña, hashearla y actualizar
        if ($request->filled('password')) {
            $usuario->password = Hash::make($request->password);
        }
        
        $usuario->save();

        return response()->json([
            'message' => 'Perfil actualizado exitosamente.',
            'usuario' => $usuario
        ]);
    }

    /**
     * [ADMIN] Elimina un usuario (cliente).
     * DELETE /api/clientes/{id}
     */
    public function destroy($id)
    {
        $usuario = Usuario::find($id);

        if (!$usuario) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Evitar que un administrador se elimine a sí mismo
        if ($usuario->rol === 'administrador') {
            return response()->json(['message' => 'No puedes eliminar a un administrador.'], 403);
        }

        $usuario->delete();

        return response()->json(['message' => 'Usuario (cliente) eliminado con éxito.']);
    }
}