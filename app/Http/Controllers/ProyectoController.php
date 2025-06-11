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
    $proyectos = Proyecto::withCount('bloques')->get();

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
        'id_proyecto' => 'required|string|max:20|unique:proyectos,id_proyecto',
        'nombre' => 'required|string|max:100',
    ]);

    Proyecto::create([
        'id_proyecto' => $request->input('id_proyecto'),
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
        'id_proyecto' => 'required|string|max:20|unique:proyectos,id_proyecto,' . $proyecto->id_proyecto . ',id_proyecto',
    ]);

    // Si se cambió el id_proyecto, hay que hacer un cambio manual
    if ($proyecto->id_proyecto !== $request->id_proyecto) {
        // Creamos un nuevo registro con el nuevo ID y los mismos datos
        $proyecto->update([
            'id_proyecto' => $request->id_proyecto,
            'nombre' => $request->nombre,
        ]);
    } else {
        $proyecto->update([
            'nombre' => $request->nombre,
        ]);
    }

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
