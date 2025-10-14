<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    // GET /api/categorias
    public function index()
    {
        // Solo necesitamos el listado básico de categorías
        $categorias = Categoria::all();

        return response()->json($categorias);
    }
    
    // Las funciones 'store', 'update' y 'destroy' solo las usaría el Administrador
    // Se omiten por sencillez, se manejan en el apartado de Administración.
}