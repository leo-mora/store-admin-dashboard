<?php

namespace App\Exports;

use App\Models\Venta;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ReporteVentasExport implements FromCollection, WithMapping, WithHeadings, WithStyles, ShouldAutoSize, WithEvents
{
    protected $fecha_inicio;
    protected $fecha_fin;
    protected $cliente;

    protected $totalVentas = 0;
    protected $totalVendido = 0;
    protected $totalDescuento = 0;
    protected $totalProductosVendidos = 0;
    protected $totalDevuelto = 0;
    protected $ventasNetas = 0;

    public function __construct($fecha_inicio, $fecha_fin, $cliente)
    {
        $this->fecha_inicio = $fecha_inicio;
        $this->fecha_fin = $fecha_fin;
        $this->cliente = $cliente;
    }

    public function collection()
    {
        $ventas = Venta::with(['cliente', 'detalles.producto', 'devoluciones'])
            ->when($this->fecha_inicio, function ($q) {
                $q->whereDate('created_at', '>=', $this->fecha_inicio);
            })
            ->when($this->fecha_fin, function ($q) {
                $q->whereDate('created_at', '<=', $this->fecha_fin);
            })
            ->when($this->cliente, function ($q) {
                $q->whereHas('cliente', function ($sub) {
                    $sub->where('nombre', 'like', '%' . $this->cliente . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->get();

        $this->totalVentas = $ventas->count();
        $this->totalVendido = $ventas->sum('total');
        $this->totalDescuento = $ventas->sum('descuento');
        $this->totalProductosVendidos = $ventas->sum(function ($venta) {
            return $venta->detalles->sum('cantidad');
        });
        $this->totalDevuelto = $ventas->sum(function ($venta) {
            return $venta->devoluciones->sum('total_devuelto');
        });
        $this->ventasNetas = $this->totalVendido - $this->totalDevuelto;

        return $ventas;
    }

    public function headings(): array
    {
        return [
            'ID Venta',
            'Cliente',
            'Identificación',
            'Productos',
            'Cantidades',
            'Subtotal',
            'Descuento',
            'Total Venta',
            'Total Devuelto',
            'Venta Neta',
            'Fecha'
        ];
    }

    public function map($venta): array
    {
        $productos = $venta->detalles->map(function ($detalle) {
            $nombre = $detalle->producto?->nombre ?? 'Producto eliminado';
            $marca = $detalle->producto?->marca ? ' (' . $detalle->producto->marca . ')' : '';
            $sku = $detalle->producto?->sku ? ' [' . $detalle->producto->sku . ']' : '';
            return $nombre . $marca . $sku;
        })->implode("\n");

        $cantidades = $venta->detalles->map(function ($detalle) {
            return $detalle->cantidad;
        })->implode("\n");

        $subtotal = $venta->detalles->sum(function ($detalle) {
            return $detalle->precio * $detalle->cantidad;
        });

        $devuelto = $venta->devoluciones->sum('total_devuelto');
        $neto = $venta->total - $devuelto;

        return [
            $venta->id,
            $venta->cliente?->nombre ?? 'Sin cliente',
            $venta->cliente?->identificacion ?? 'Sin identificación',
            $productos,
            $cantidades,
            $subtotal,
            $venta->descuento,
            $venta->total,
            $devuelto,
            $neto,
            $venta->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0D6EFD'],
                ],
            ],
            2 => [
                'font' => [
                    'italic' => true,
                    'color' => ['rgb' => '555555'],
                ],
            ],
            4 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '198754'],
                ],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // Insertar filas arriba
                $sheet->insertNewRowBefore(1, 3);

                // Título
                $sheet->setCellValue('A1', 'REPORTE DE VENTAS');
                $sheet->mergeCells('A1:K1');
                $sheet->getRowDimension(1)->setRowHeight(28);

                // Fecha de generación
                $sheet->setCellValue('A2', 'Fecha de generación: ' . now()->format('d/m/Y H:i'));
                $sheet->mergeCells('A2:K2');

                // Resumen
                $sheet->setCellValue('M1', 'RESUMEN');
                $sheet->setCellValue('M2', 'Total de ventas');
                $sheet->setCellValue('N2', $this->totalVentas);

                $sheet->setCellValue('M3', 'Total vendido');
                $sheet->setCellValue('N3', $this->totalVendido);

                $sheet->setCellValue('M4', 'Total descuento');
                $sheet->setCellValue('N4', $this->totalDescuento);

                $sheet->setCellValue('M5', 'Productos vendidos');
                $sheet->setCellValue('N5', $this->totalProductosVendidos);

                $sheet->setCellValue('M6', 'Total devuelto');
                $sheet->setCellValue('N6', $this->totalDevuelto);

                $sheet->setCellValue('M7', 'Ventas netas');
                $sheet->setCellValue('N7', $this->ventasNetas);

                // Estilo título resumen
                $sheet->getStyle('M1:N1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'DC3545'],
                    ],
                ]);

                $sheet->mergeCells('M1:N1');

                // Estilo labels resumen
                $sheet->getStyle('M2:M7')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8D7DA'],
                    ],
                ]);

                // Estilo valores resumen
                $sheet->getStyle('N2:N7')->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFF3CD'],
                    ],
                ]);

                // Bordes tabla principal
                $lastRow = $sheet->getHighestRow();

                $sheet->getStyle("A4:K{$lastRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D9D9D9'],
                        ],
                    ],
                ]);

                // Bordes resumen
                $sheet->getStyle('M1:N7')->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'BFBFBF'],
                        ],
                    ],
                ]);

                // Ajuste vertical y wrap
                $sheet->getStyle("A4:K{$lastRow}")->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
                $sheet->getStyle("D5:E{$lastRow}")->getAlignment()->setWrapText(true);

                // Filas alternas
                for ($row = 5; $row <= $lastRow; $row++) {
                    if ($row % 2 == 0) {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'F8F9FA'],
                            ],
                        ]);
                    }
                }

                // Formato moneda en tabla principal
                $sheet->getStyle("F5:J{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"₡" #,##0.00');

                $sheet->getStyle("F5:J{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Centrar algunas columnas
                $sheet->getStyle("A5:A{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getStyle("E5:E{$lastRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                // Formato correcto para resumen
                $sheet->getStyle("N2")->getNumberFormat()->setFormatCode('0');
                $sheet->getStyle("N5")->getNumberFormat()->setFormatCode('0');

                $sheet->getStyle("N3")->getNumberFormat()->setFormatCode('"₡" #,##0.00');
                $sheet->getStyle("N4")->getNumberFormat()->setFormatCode('"₡" #,##0.00');
                $sheet->getStyle("N6")->getNumberFormat()->setFormatCode('"₡" #,##0.00');
                $sheet->getStyle("N7")->getNumberFormat()->setFormatCode('"₡" #,##0.00');

                // Alinear resumen a la derecha
                $sheet->getStyle("N2:N7")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                // Dar más ancho al resumen para evitar #######
                $sheet->getColumnDimension('M')->setWidth(25);
                $sheet->getColumnDimension('N')->setWidth(20);
            },
        ];
    }
}