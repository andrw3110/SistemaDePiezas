<?php

namespace App\Exports;

use App\Models\Proyecto;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProyectosPiezasExport implements FromCollection, WithHeadings, ShouldAutoSize, WithTitle, WithCustomStartCell, WithStyles
{
    public function collection()
    {
        $proyectos = Proyecto::all();
        $data = collect();

        foreach ($proyectos as $proyecto) {
            $piezasFabricadas = $proyecto->piezas()->where('Estado', 'Fabricado')->count();
            $piezasPendientes = $proyecto->piezas()->where('Estado', 'Pendiente')->count();
            $totalPiezasProyecto = $piezasFabricadas + $piezasPendientes;

            $data->push([
                'proyecto_nombre' => $proyecto->nombre,
                'piezas_fabricadas' => $piezasFabricadas,
                'piezas_pendientes' => $piezasPendientes,
                'total_piezas_proyecto' => $totalPiezasProyecto,
            ]);
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nombre del Proyecto',
            'Piezas Fabricadas',
            'Piezas Pendientes',
            'Total Piezas',
        ];
    }

    public function title(): string
    {
        return 'Reporte de Piezas por Proyecto';
    }

    public function startCell(): string
    {
        return 'A3';
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setCellValue('A1', 'Resumen de Piezas por Proyecto');
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'name' => 'Arial',
                'size' => 18,
                'bold' => true,
                'color' => ['argb' => 'FF2C3E50'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [
            3 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FF2A9D8F',
                    ],
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ],
        ];
    }
}