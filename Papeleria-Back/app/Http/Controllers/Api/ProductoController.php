<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    // GET /api/productos
    public function index(Request $request)
    {
        // 🚨 MODIFICACIÓN CLAVE PARA PRODUCTOS ALEATORIOS
        $limit = $request->query('limit', 5); // Obtiene el límite del query string, por defecto 5
        
        $productos = Producto::with('categoria', 'proveedor')
                             ->inRandomOrder() // Selecciona productos en orden aleatorio
                             ->limit($limit)   // Limita el número de resultados
                             ->get();

        return response()->json($productos);
    }


    // GET /api/productos/{id}
    public function show($id)
    {
        // Busca un producto por ID, incluyendo sus relaciones
        $producto = Producto::with('categoria', 'proveedor')->find($id);

        if (!$producto) {
            return response()->json(['message' => 'Producto no encontrado'], 404);
        }

        return response()->json($producto);
    }

    public function search(Request $request)
    {
        $query = $request->input('query'); // Captura el texto de búsqueda

        if (!$query) {
            return response()->json([]); // Retorna un array vacío si no hay consulta
        }

        $productos = Producto::with('categoria')
            ->where('nombre', 'LIKE', '%' . $query . '%')
            ->orWhere('descripcion', 'LIKE', '%' . $query . '%')
            ->get();

        return response()->json($productos);
    }

    /**
     * GET /api/productos/categoria/{id}
     * Filtrar productos por una categoría específica.
     */
    public function filterByCategory($id)
    {
        $productos = Producto::with('categoria')
            ->where('categoria_id', $id)
            ->get();

        if ($productos->isEmpty()) {
            return response()->json(['message' => 'No se encontraron productos en esta categoría.'], 404);
        }

        return response()->json($productos);
    }
    
    // Las funciones 'store', 'update' y 'destroy' solo las usaría el Administrador
    // Las implementaremos en el apartado de Administración.
}