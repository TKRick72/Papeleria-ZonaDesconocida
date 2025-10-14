<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\Usuario;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    // GET /api/dashboard - Muestra métricas clave (Admin)
    public function index()
    {
        // 1. Métricas de Ventas (ERP)
        $totalVentas = Pedido::where('estado', 'entregado')->sum('total');
        $pedidosHoy = Pedido::whereDate('fecha_pedido', today())->count();
        $pedidosPendientes = Pedido::where('estado', 'procesando')->count();

        // 2. Métricas de Inventario (ERP/SCM)
        $totalProductos = Producto::count();
        $productosBajoStock = Producto::where('stock', '<', 10)->count(); // Define 'bajo stock' como < 10

        // 3. Métricas de Clientes (CRM)
        $totalClientes = Usuario::where('rol', 'usuario')->count();

        // 4. Productos Más Vendidos (ERP)
        $topProductos = DB::table('detalle_pedido')
                          ->select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
                          ->groupBy('producto_id')
                          ->orderByDesc('total_vendido')
                          ->limit(5)
                          ->get();

        // 🚨 CÁLCULO DE RENTABILIDAD POR PROVEEDOR (SCM/ERP)
    $proveedorRentable = DB::table('detalle_pedido')
        // Unimos pedidos, detalles, y productos
        ->join('productos', 'detalle_pedido.producto_id', '=', 'productos.id')
        ->join('proveedores', 'productos.proveedor_id', '=', 'proveedores.id')
        
        // Calculamos la ganancia total (Precio de Venta - Costo Neto)
        ->select('proveedores.nombre as proveedor_nombre',
                 DB::raw('SUM(detalle_pedido.cantidad * (detalle_pedido.precio_unitario - productos.costo)) AS ganancia_neta'))
        
        ->groupBy('proveedores.nombre')
        ->orderByDesc('ganancia_neta')
        ->first(); // Obtenemos solo el más rentable

        return response()->json([
            'resumen_ventas' => [
                'total_ventas_historial' => number_format($totalVentas, 2),
                'pedidos_pendientes' => $pedidosPendientes,
                'nuevos_pedidos_hoy' => $pedidosHoy,
            ],
            'resumen_inventario' => [
                'total_productos' => $totalProductos,
                'productos_bajo_stock' => $productosBajoStock,
            ],
            'resumen_clientes' => [
                'total_clientes' => $totalClientes,
            ],
            'top_5_vendidos' => $topProductos,

            'mejor_proveedor' => $proveedorRentable,
        ]);
    }
}