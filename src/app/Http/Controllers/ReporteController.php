<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReporteVentasExport;
use App\Helpers\BitacoraHelper;

class ReporteController extends Controller
{
    public function index(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $cliente = trim($request->cliente ?? '');

        $query = Venta::with(['cliente', 'devoluciones'])
            ->when($fecha_inicio, function ($q) use ($fecha_inicio) {
                $q->whereDate('created_at', '>=', $fecha_inicio);
            })
            ->when($fecha_fin, function ($q) use ($fecha_fin) {
                $q->whereDate('created_at', '<=', $fecha_fin);
            })
            ->when($cliente, function ($q) use ($cliente) {
                $q->whereHas('cliente', function ($sub) use ($cliente) {
                    $sub->where('nombre', 'like', "%{$cliente}%");
                });
            })
            ->orderBy('id', 'desc');

        $ventas = (clone $query)->paginate(10)->withQueryString();
        $resumen = (clone $query)->get();

        $totalVentas = $resumen->count();
        $totalVendido = $resumen->sum('total');
        $totalClientes = $resumen->pluck('cliente_id')->filter()->unique()->count();

        $totalDevuelto = $resumen->sum(function ($venta) {
            return $venta->devoluciones->sum('total_devuelto');
        });

        $cantidadDevoluciones = $resumen->sum(function ($venta) {
            return $venta->devoluciones->count();
        });

        $ventasNetas = $totalVendido - $totalDevuelto;

        return view('reportes.index', compact(
            'ventas',
            'fecha_inicio',
            'fecha_fin',
            'cliente',
            'totalVentas',
            'totalVendido',
            'totalClientes',
            'totalDevuelto',
            'cantidadDevoluciones',
            'ventasNetas'
        ));
    }

    public function exportarExcel(Request $request)
    {
        $fecha_inicio = $request->fecha_inicio;
        $fecha_fin = $request->fecha_fin;
        $cliente = trim($request->cliente ?? '');

        BitacoraHelper::registrar(
            'Reportes',
            'Exportar Excel',
            'Se exportó el reporte de ventas a Excel con filtros - Fecha inicio: ' .
            ($fecha_inicio ?: 'Sin filtro') .
            ', Fecha fin: ' . ($fecha_fin ?: 'Sin filtro') .
            ', Cliente: ' . ($cliente ?: 'Sin filtro')
        );

        return Excel::download(
            new ReporteVentasExport($fecha_inicio, $fecha_fin, $cliente),
            'reporte_ventas.xlsx'
        );
    }
}