<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Proyecto;
use App\Models\Bloque;
use App\Models\Pieza;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Aquí se registran las rutas de la API. Estas rutas están protegidas
| por el middleware 'api' y pueden ser usadas por el frontend de Vue.
|
*/

// Ruta para obtener el usuario autenticado (por defecto de Jetstream)
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// 🔐 Rutas protegidas que requieren autenticación
Route::middleware('auth:sanctum')->group(function () {

    // 📊 Endpoint para el dashboard: resumen general + últimas piezas
    Route::get('/resumen-dashboard', function () {
        return response()->json([
            'resumen' => [
                'proyectos' => Proyecto::count(),
                'bloques'   => Bloque::count(),
                'piezas'    => Pieza::count(),
            ],
            'piezas' => Pieza::with(['proyecto', 'bloque'])
                            ->latest('id_pieza')
                            ->take(5)
                            ->get(),
        ]);
    });

});
