<?php

namespace App\Http\Controllers;

use App\Models\Pieza;
use App\Models\Proyecto;
use App\Models\Bloque;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Totales generales
        $totalProyectos = Proyecto::count();
        $totalBloques = Bloque::count();
        $totalPiezas = Pieza::count();

        // Últimas 5 piezas registradas (con relaciones cargadas)
        $piezasRecientes = Pieza::with(['proyecto', 'bloque'])
            ->latest('fecha_registro')
            ->take(5)
            ->get();

        // Renderizar la vista Dashboard con los datos
        return Inertia::render('Dashboard', [
            'resumen' => [
                'proyectos' => $totalProyectos,
                'bloques' => $totalBloques,
                'piezas' => $totalPiezas,
            ],
            'piezasRecientes' => $piezasRecientes,
        ]);
    }
}
