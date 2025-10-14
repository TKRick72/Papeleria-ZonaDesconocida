<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Pago;
use App\Models\Envio;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // GET /api/pedidos (Para el cliente: ver mis pedidos)
    public function index(Request $request)
    {
        // Solo muestra los pedidos del usuario autenticado
        $pedidos = Pedido::where('usuario_id', $request->user()->id)
                         ->with('detalles.producto', 'envio', 'pago') // Carga relaciones
                         ->latest() // Ordena por más reciente
                         ->get();

        return response()->json($pedidos);
    }

    // POST /api/pedidos (Crear un nuevo pedido desde el carrito)
    public function store(Request $request)
    {
        // Asumimos que la validación del carrito se hace en el frontend
        // y que el request contiene: items[], total, metodo_pago, direccion_envio
        
        DB::beginTransaction();
        try {
            // 1. CREAR EL PEDIDO
            $pedido = Pedido::create([
                'usuario_id' => $request->user()->id, // Asume que el usuario está autenticado
                'total' => $request->total,
                'estado' => 'procesando'
            ]);

            // 2. CREAR DETALLES DEL PEDIDO Y ACTUALIZAR STOCK
            foreach ($request->items as $item) {
                // Asegurar que el producto exista y haya suficiente stock (Control SCM/ERP)
                $producto = Producto::find($item['producto_id']);
                if ($producto->stock < $item['cantidad']) {
                    DB::rollBack();
                    return response()->json(['message' => 'Stock insuficiente para ' . $producto->nombre], 400);
                }

                DetallePedido::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $producto->precio // Precio al momento de la compra
                ]);
                
                // Actualizar stock (Descontar del inventario)
                $producto->decrement('stock', $item['cantidad']);
            }

            // 3. CREAR PAGO
            Pago::create([
                'pedido_id' => $pedido->id,
                'metodo_pago' => $request->metodo_pago,
                'monto' => $request->total,
                'estado' => 'completado'
            ]);

            // 4. CREAR ENVÍO
            Envio::create([
                'pedido_id' => $pedido->id,
                'direccion_envio' => $request->direccion_envio,
                'estado_envio' => 'preparacion'
            ]);

            DB::commit(); // Todo fue exitoso

            return response()->json($pedido->load('detalles', 'envio'), 201);

        } catch (\Exception $e) {
            DB::rollBack(); // Algo falló, deshacer todo
            return response()->json(['message' => 'Error al procesar el pedido: ' . $e->getMessage()], 500);
        }
    }
}