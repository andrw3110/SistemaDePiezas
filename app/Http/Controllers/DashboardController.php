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
        $totalPiezas = Pieza::count(); // Esto es el total general de todas las piezas

        // Conteo de piezas por estado para la gráfica de dona
        $piezasFabricadas = Pieza::where('estado', 'Fabricado')->count();
        $piezasPendientes = Pieza::where('estado', 'Pendiente')->count();

        // Últimas 5 piezas registradas (con relaciones cargadas)
        // Usamos 'latest()' para ordenar por 'created_at' o por la columna que defina el último registro.
        // Si 'fecha_registro' es la columna de la fecha de registro, entonces 'latest('fecha_registro')' es correcto.
        $piezasRecientes = Pieza::with(['proyecto', 'bloque'])
            ->latest('fecha_registro') // Asumiendo que 'fecha_registro' es tu columna de fecha
            ->take(5)
            ->get();

        // Renderizar la vista Dashboard con los datos
        return Inertia::render('Dashboard', [
            'resumen' => [
                'proyectos' => $totalProyectos,
                'bloques' => $totalBloques,
                'piezas' => $totalPiezas,
                // ¡Añadimos las piezas fabricadas y pendientes aquí!
                'piezas_fabricadas' => $piezasFabricadas,
                'piezas_pendientes' => $piezasPendientes,
            ],
            'piezasRecientes' => $piezasRecientes,
        ]);
    }
}