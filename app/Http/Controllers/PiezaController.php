<?php

namespace App\Http\Controllers;

use App\Models\Pieza;
use App\Models\Bloque;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

class PiezaController extends Controller
{
    /**
     * Muestra la vista principal con la lista de piezas y datos relacionados.
     */
    public function index()
    {        
        $proyecto = Proyecto::all();
        $usuarioNombre = Auth::user()->name; 

        return Inertia::render('PiezasComponent', [
           
            'piezas' => Pieza::with('proyecto', 'bloque')->get(),
            'proyectos' => Proyecto::all(),
            'bloques' => Bloque::all(),
            'usuarioNombre' => Auth::user()->name,
        ]);
    }

    /**
     * Guarda una nueva pieza en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
        'pieza' => 'required|max:10',
        'peso_teorico' => 'required|numeric',
        'estado' => 'required|in:Fabricado,Pendiente',
        'id_proyecto' => 'required|exists:proyectos,id_proyecto',
        'id_bloque' => 'required|exists:bloques,id_bloque',
        ]);

        $request->merge([
            'id_pieza' => Str::uuid(), // o tu lógica de ID de 10 caracteres
        ]);

        Pieza::create($request->all());

        return Redirect::route('piezas.index')->with('success', 'Pieza creada correctamente.');
    }

    /**
     * Actualiza los datos de una pieza existente.
     */
    public function update(Request $request, Pieza $pieza)
    {
        $request->validate([
            'pieza' => 'required|max:10',
            'peso_teorico' => 'required|numeric',
            'estado' => 'required|in:Fabricado,Pendiente',
            'id_proyecto' => 'required|exists:proyectos,id_proyecto',
            'id_bloque' => 'required|exists:bloques,id_bloque',
        ]);

        $pieza->update($request->all());

        return Redirect::route('piezas.index')->with('success', 'Pieza actualizada correctamente.');
    }

    /**
     * Elimina una pieza específica.
     */
    public function destroy(Pieza $pieza)
    {
        $pieza->delete();
        return Redirect::route('piezas.index')->with('success', 'Pieza eliminada correctamente.');
    }
}
