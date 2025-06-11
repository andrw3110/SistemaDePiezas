<?php

namespace App\Http\Controllers;

use App\Models\Bloque;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class BloqueController extends Controller
{
    /**
     * Muestra la vista principal de bloques con sus proyectos relacionados.
     */
    public function index()
    {
        $bloques = Bloque::with('proyecto')->get();

        return Inertia::render('BloqueComponent', [
            'bloques' => $bloques,
            'proyectos' => Proyecto::all(),
        ]);
    }

    /**
     * Almacena un nuevo bloque en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_bloque' => 'required|string|max:10',
            'id_proyecto' => 'required|string|exists:proyectos,id_proyecto',
        ]);

        $idBloque = strtoupper(Str::random(10));
        while (Bloque::where('id_bloque', $idBloque)->exists()) {
            $idBloque = strtoupper(Str::random(10));
        }

        Bloque::create([
            'id_bloque' => $idBloque,
            'nombre_bloque' => $request->input('nombre_bloque'),
            'id_proyecto' => $request->input('id_proyecto'),
        ]);

        return redirect()->route('bloques.index')->with('success', 'Bloque creado correctamente.');
    }

    /**
     * Actualiza los datos de un bloque existente.
     */
    public function update(Request $request, Bloque $bloque)
    {
        $request->validate([
            'nombre_bloque' => 'required|string|max:10',
            'id_proyecto' => 'required|string|exists:proyectos,id_proyecto',
        ]);

        $bloque->update([
            'nombre_bloque' => $request->input('nombre_bloque'),
            'id_proyecto' => $request->input('id_proyecto'),
        ]);

        return redirect()->route('bloques.index')->with('success', 'Bloque actualizado correctamente.');
    }

    /**
     * Elimina un bloque específico.
     */
    public function destroy(Bloque $bloque)
    {
        $bloque->delete();

        return redirect()->route('bloques.index')->with('success', 'Bloque eliminado correctamente.');
    }
}

