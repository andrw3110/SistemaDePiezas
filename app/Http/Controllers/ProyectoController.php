<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class ProyectoController extends Controller
{
    /**
     * Muestra la lista de todos los proyectos en la vista ProyectoComponent.
     */
    public function index()
    {
        $proyectos = Proyecto::all();

        return Inertia::render('ProyectoComponent', [
            'proyectos' => $proyectos,
            'flash' => session('message')
        ]);
    }

    /**
     * Guarda un nuevo proyecto en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $idProyecto = strtoupper(Str::random(10));
        while (Proyecto::where('id_proyecto', $idProyecto)->exists()) {
            $idProyecto = strtoupper(Str::random(10));
        }

        Proyecto::create([
            'id_proyecto' => $idProyecto,
            'nombre' => $request->input('nombre'),
        ]);

        return Redirect::back()->with('message', 'Proyecto creado correctamente.');
    }

    /**
     * Actualiza un proyecto existente.
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
        ]);

        $proyecto->update([
            'nombre' => $request->input('nombre'),
        ]);

        return Redirect::back()->with('message', 'Proyecto actualizado correctamente.');
    }

    /**
     * Elimina un proyecto de la base de datos.
     */
    public function destroy(Proyecto $proyecto)
    {
        $proyecto->delete();

        return Redirect::back()->with('message', 'Proyecto eliminado correctamente.');
    }
}
