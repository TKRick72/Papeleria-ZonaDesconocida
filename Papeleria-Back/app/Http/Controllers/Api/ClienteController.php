<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    // GET /api/clientes - Muestra todos los usuarios con rol 'usuario'
    public function index()
    {
        $clientes = Usuario::where('rol', 'usuario')
                           ->orderBy('nombre')
                           ->get();

        return response()->json($clientes);
    }

    // GET /api/clientes/{id} - Muestra los detalles de un cliente
    public function show($id)
    {
        $cliente = Usuario::where('rol', 'usuario')->find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        return response()->json($cliente);
    }

    // POST /api/clientes - Crea un nuevo cliente (opcional, si el admin registra)
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'email' => 'required|email|unique:usuarios,email',
            'password' => 'required|string|min:6',
        ]);

        $cliente = Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => $request->password, // Se hashea automáticamente con el Mutator
            'rol' => 'usuario' // Siempre es 'usuario' en este controlador
        ]);

        return response()->json($cliente, 201);
    }

    // PUT/PATCH /api/clientes/{id} - Actualiza datos de un cliente
    public function update(Request $request, $id)
    {
        $cliente = Usuario::where('rol', 'usuario')->find($id);

        if (!$cliente) {
            return response()->json(['message' => 'Cliente no encontrado.'], 404);
        }

        $request->validate([
            'nombre' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:usuarios,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);
        
        $cliente->fill($request->except('password'));

        if ($request->filled('password')) {
            $cliente->password = Hash::make($request->password);
        }

        $cliente->save();

        return response()->json($cliente);
    }

    // DELETE /api/clientes/{id} - Elimina un cliente
    public function destroy($id)
    {
        if (Usuario::where('rol', 'usuario')->where('id', $id)->delete()) {
            return response()->json(['message' => 'Cliente eliminado con éxito.']);
        }
        return response()->json(['message' => 'Cliente no encontrado o no autorizado para eliminar.'], 404);
    }
}