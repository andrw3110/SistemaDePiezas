<?php

namespace App\Http\Controllers;

use App\Exports\ProyectosPiezasExport; // Importa tu clase de exportación
use Maatwebsite\Excel\Facades\Excel; // Importa el Facade de Excel
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    /**
     * Exporta el reporte de piezas por proyecto a un archivo Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportProyectosPiezas()
    {
        // Genera un nombre de archivo único con la fecha y hora actuales
        $fileName = 'reporte_piezas_por_proyecto_' . now()->format('Ymd_His') . '.xlsx';

        // Descarga el archivo Excel usando la clase de exportación ProyectosPiezasExport
        return Excel::download(new ProyectosPiezasExport, $fileName);
    }
}