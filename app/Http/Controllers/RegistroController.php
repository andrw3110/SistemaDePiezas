<?php

namespace App\Http\Controllers;

use App\Models\Registro;
use App\Models\Bloque;
use App\Models\Pieza;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class RegistroController extends Controller
{
    public function index()
    {
        $usuarioNombre = Auth::user()->name; 
        $registros = Registro::with('bloque')->get();
        $bloques = Bloque::all();
        $piezas = Pieza::all();

        return Inertia::render('RegistrosComponent', [
            'registros' => $registros,
            'bloques' => $bloques,
            'usuarioNombre' => $usuarioNombre,
            'piezas' => $piezas
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'pieza' => 'required|string|max:10',
            'peso_teorico' => 'required|numeric',
            'peso_real' => 'nullable|numeric',
            'estado' => 'required|in:Fabricado,Pendiente',
            'id_bloque' => 'required|string|exists:bloques,id_bloque',
            'fecha_registro' => 'required|date',
            'registrado_por' => 'nullable|string|max:50',
        ]);

        Registro::create([
            'id_pieza' => $request->input('id_pieza'),
            'pieza' => $request->input('pieza'),
            'peso_teorico' => $request->input('peso_teorico'),
            'peso_real' => $request->input('peso_real'),
            'estado' => $request->input('estado'),
            'id_bloque' => $request->input('id_bloque'),
            'fecha_registro' => $request->input('fecha_registro'),
            'registrado_por' => $request->input('registrado_por'),
        ]);

        return redirect()->route('registros.index')->with('success', 'Registro creado correctamente.');
    }

    public function update(Request $request, Registro $registro)
    {
        $request->validate([
            'pieza' => 'required|string|max:10',
            'peso_teorico' => 'required|numeric',
            'peso_real' => 'nullable|numeric',
            'estado' => 'required|in:Fabricado,Pendiente',
            'id_bloque' => 'required|string|exists:bloques,id_bloque',
            'fecha_registro' => 'required|date',
            'registrado_por' => 'nullable|string|max:50',
        ]);

        $registro->update([
            'pieza' => $request->input('pieza'),
            'peso_teorico' => $request->input('peso_teorico'),
            'peso_real' => $request->input('peso_real'),
            'estado' => $request->input('estado'),
            'id_bloque' => $request->input('id_bloque'),
            'fecha_registro' => $request->input('fecha_registro'),
            'registrado_por' => $request->input('registrado_por'),
        ]);

        return redirect()->route('registros.index')->with('success', 'Registro actualizado correctamente.');
    }

    public function destroy($id)
    {
        $registro = Registro::findOrFail($id);
        $registro->delete();

        return redirect()->route('registros.index')->with('success', 'Registro eliminado correctamente.');
    }
}
