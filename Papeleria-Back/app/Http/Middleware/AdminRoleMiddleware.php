<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificar si hay un usuario autenticado
        if (!$request->user()) {
            // Error 401: No autorizado (No hay token o es inválido)
            return response()->json(['message' => 'Acceso denegado. No autenticado.'], 401);
        }

        // 2. Verificar el rol del usuario
        if ($request->user()->rol !== 'administrador') {
            // Error 403: Prohibido (El usuario está logueado pero no tiene el rol correcto)
            return response()->json(['message' => 'Acceso denegado. Se requiere rol de administrador.'], 403);
        }

        // Si es administrador, permitir la solicitud
        return $next($request);
    }
}