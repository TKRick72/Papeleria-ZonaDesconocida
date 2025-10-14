<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    // GET /api/proveedores
    public function index()
    {
        return response()->json(Proveedor::all());
    }

    // POST /api/proveedores
    public function store(Request $request)
    {
        $fields = $request->validate([
            'nombre' => 'required|string|max:100',
            'contacto_email' => 'nullable|email',
            'telefono' => 'nullable|string|max:20',
        ]);

        $proveedor = Proveedor::create($fields);

        return response()->json($proveedor, 201);
    }
    
    // DELETE /api/proveedores/{id}
    public function destroy($id)
    {
        Proveedor::destroy($id);

        return response()->json(['message' => 'Proveedor eliminado con éxito']);
    }
    
    // (Omitimos show y update por sencillez, pero siguen el mismo patrón de ProductoController)
}