<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Envio;
use Illuminate\Http\Request;

class EnvioController extends Controller
{
    // GET /api/envios - Lista todos los envíos (Admin)
    public function index()
    {
        // Precarga el pedido y el usuario asociado al envío
        $envios = Envio::with('pedido.usuario') 
                       ->orderBy('created_at', 'desc')
                       ->get();

        return response()->json($envios);
    }

    // GET /api/envios/{id} - Muestra el detalle de un envío (Admin)
    public function show($id)
    {
        $envio = Envio::with('pedido.usuario', 'pedido.detalles.producto')->find($id);

        if (!$envio) {
            return response()->json(['message' => 'Envío no encontrado.'], 404);
        }

        return response()->json($envio);
    }

    // PUT/PATCH /api/envios/{id} - Actualiza el estado del envío (Admin)
    public function update(Request $request, $id)
    {
        $envio = Envio::find($id);

        if (!$envio) {
            return response()->json(['message' => 'Envío no encontrado.'], 404);
        }

        $request->validate([
            'estado_envio' => 'required|in:preparacion,transito,reparto,entregado,cancelado',
            'codigo_seguimiento' => 'nullable|string|max:100',
        ]);
        
        $envio->update($request->all());

        // Opcional: Si el estado es 'entregado', también se podría actualizar el estado del pedido
        if ($envio->estado_envio === 'entregado') {
            $envio->pedido->update(['estado' => 'entregado']);
        }

        return response()->json($envio);
    }
    
    // Las funciones 'store' y 'destroy' se manejan en PedidoController por sencillez.
}