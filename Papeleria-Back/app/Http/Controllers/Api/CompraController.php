<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompraController extends Controller
{
    /**
     * [ADMIN/SCM] Simula la recepción de mercancía (Orden de Compra).
     * POST /api/compras
     * * El cuerpo de la solicitud (Request) debe contener un array de ítems 
     * comprados y la información del proveedor.
     */
   // ...
public function store(Request $request)
{
    // ... validación y beginTransaction ...

    $proveedor = Proveedor::find($request->proveedor_id);
    $totalCompra = 0;
    
    DB::beginTransaction();
    try {
        
        foreach ($request->items as $item) {
            $producto = Producto::find($item['producto_id']);
            
            // ... verificación de producto ...

            // Calcular el nuevo costo total de la compra (usando el costo CON IVA para el total de la compra)
            $subtotal = $item['cantidad'] * $item['costo_unitario'];
            $totalCompra += $subtotal;

            // 2. Lógica SCM/ERP: Actualizar Stock y Costo
            
            // ----------------------------------------------------
            // CÁLCULO DE COSTO NETO (Quitando el 16% de IVA)
            // ----------------------------------------------------
            $costoConIVA = $item['costo_unitario'];
            $costoNeto = $costoConIVA / 1.16; // El divisor 1.16 quita el 16% de IVA
            // ----------------------------------------------------
            
            // Opción 1 (Simple): Usar el COSTO NETO para registrar el valor real de adquisición.
            $producto->costo = $costoNeto; 
            
            // Aumentar el stock del producto
            $producto->increment('stock', $item['cantidad']);
            $producto->save();
        }

        DB::commit(); 

        return response()->json([
            'message' => 'Orden de compra registrada y stock actualizado.',
            'proveedor' => $proveedor->nombre,
            'total_compra' => number_format($totalCompra, 2),
            'items_recibidos' => count($request->items)
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json(['message' => 'Error al procesar la compra: ' . $e->getMessage()], 500);
    }
}
// ...
    
    // Omitimos index, show, update, y destroy para mantener la sencillez.
}