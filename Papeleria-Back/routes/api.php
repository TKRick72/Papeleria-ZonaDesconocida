<?php

use Illuminate\Support\Facades\Route;

// Importar los Controladores de API que creamos
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\PedidoController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\ClienteController;
use App\Http\Controllers\Api\EnvioController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\DashboardController;


/*
|--------------------------------------------------------------------------
| RUTAS PÚBLICAS Y DE AUTENTICACIÓN
| Estas rutas son accesibles por cualquier persona (no requieren token).
|--------------------------------------------------------------------------
*/

// Autenticación (Login/Registro)
Route::post('/registro', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Catálogo Público
Route::get('/productos', [ProductoController::class, 'index']); // Listar productos
Route::get('/productos/{id}', [ProductoController::class, 'show']); // Ver detalle
Route::get('/categorias', [CategoriaController::class, 'index']); // Listar categorías
// Búsqueda por Texto (Nueva)
Route::get('/productos/buscar', [ProductoController::class, 'search']);

// Filtrar por Categoría (Nueva)
Route::get('/productos/categoria/{id}', [ProductoController::class, 'filterByCategory']);

Route::get('/productos/{id}', [ProductoController::class, 'show']); // Ver detalle
Route::get('/productos', [ProductoController::class, 'index']); // Listar productos


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS (Requieren que el usuario esté logueado - Token SANCTUM)
| Usamos el middleware 'auth:sanctum' para proteger el acceso.
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->group(function () {
    
    // CRM: Funcionalidades del Cliente (Mi Perfil)
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/usuario', [UsuarioController::class, 'show']); // Ver datos del usuario logueado
    Route::put('/usuario', [UsuarioController::class, 'update']); // Actualizar perfil

    // ERP: Funcionalidades de Pedidos (Hacer una compra)
    Route::get('/pedidos', [PedidoController::class, 'index']); // Ver mi historial de pedidos
    Route::post('/pedidos', [PedidoController::class, 'store']); // Crear un nuevo pedido (el carrito)

    /*
    |--------------------------------------------------------------------------
    | RUTAS DE ADMINISTRACIÓN (Protegidas y con verificación de Rol)
    |--------------------------------------------------------------------------
    */
    
    // Usaremos un middleware 'admin' que deberás crear para verificar el rol.
    // De momento, solo las agrupamos dentro de 'auth:sanctum'
    Route::middleware(['auth:sanctum', 'admin'])->group(function () {

        // CRUD de Productos y Categorías (ERP)
        Route::apiResource('productos', ProductoController::class)->except(['index', 'show']);
        Route::apiResource('categorias', CategoriaController::class)->except(['index']);

        // CRUD de Proveedores (SCM)
        Route::apiResource('proveedores', ProveedorController::class);

        // Gestión de Clientes (CRM)
        Route::apiResource('clientes', ClienteController::class);

        // Gestión de Logística y Pagos (SCM/ERP)
        Route::apiResource('envios', EnvioController::class)->only(['index', 'show', 'update']); // Administrar envíos
        Route::apiResource('pagos', PagoController::class)->only(['index', 'show']); // Ver registros de pagos

        // SCM: Gestión de Compras y Re-stock
        Route::post('/compras', [CompraController::class, 'store']); // Registrar recepción de mercancía

        // Dashboard y Métricas (ERP/SCM/CRM)
        Route::get('/dashboard', [DashboardController::class, 'index']); // Métr

    });

});