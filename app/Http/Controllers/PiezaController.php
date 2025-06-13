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
        // Verifica que Auth::user() no sea nulo antes de acceder a sus propiedades
        $usuarioNombre = Auth::check() ? (Auth::user()->Usuario ?? Auth::user()->name) : 'Invitado'; 

        return Inertia::render('PiezasComponent', [
            'piezas' => Pieza::with('proyecto', 'bloque')->get(),
            'proyectos' => Proyecto::all(),
            'bloques' => Bloque::all(),
            'usuarioNombre' => $usuarioNombre,
        ]);
    }

    /**
     * Guarda una nueva pieza en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'pieza' => 'required|string|max:10',
            'peso_teorico' => 'required|numeric',
            'estado' => 'required|in:Fabricado,Pendiente',
            'id_proyecto' => 'required|string|exists:proyectos,id_proyecto',
            'id_bloque' => 'required|string|exists:bloques,id_bloque',
        ]);

        // Generar un ID de 10 caracteres único para 'id_pieza'
        $request->merge([
            'id_pieza' => Str::random(10), // <-- ¡CORREGIDO A id_pieza (minúsculas)!
            'fecha_registro' => now(), // Añadir fecha y hora actuales
            'registrado_por' => Auth::id(), // ID del usuario autenticado
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
            'pieza' => 'required|string|max:10',
            'peso_teorico' => 'required|numeric',
            'estado' => 'required|in:Fabricado,Pendiente',
            'id_proyecto' => 'required|string|exists:proyectos,id_proyecto',
            'id_bloque' => 'required|string|exists:bloques,id_bloque',
        ]);

        // Asegúrate de que el estado y los campos de registro se actualicen si se ingresa peso real
        if ($request->filled('peso_real') && $request->estado === 'Fabricado') {
             $request->merge([
                'fecha_registro' => now(),
                'registrado_por' => Auth::id(),
            ]);
        }

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