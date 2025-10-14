<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;

class PagoController extends Controller
{
    // GET /api/pagos - Lista todas las transacciones de pago (Admin)
    public function index()
    {
        // Precarga el pedido y el usuario asociado al pago
        $pagos = Pago::with('pedido.usuario')
                     ->orderBy('fecha_pago', 'desc')
                     ->get();

        return response()->json($pagos);
    }

    // GET /api/pagos/{id} - Muestra el detalle de una transacción (Admin)
    public function show($id)
    {
        $pago = Pago::with('pedido.usuario')->find($id);

        if (!$pago) {
            return response()->json(['message' => 'Pago no encontrado.'], 404);
        }

        return response()->json($pago);
    }

    // Omitimos store, update y destroy porque la creación/actualización del pago
    // se gestiona dentro del flujo del PedidoController por sencillez.
}