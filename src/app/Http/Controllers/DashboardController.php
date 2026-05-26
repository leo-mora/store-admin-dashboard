<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Venta;
use App\Models\DetalleVenta;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $productos = Producto::count();
        $clientes = Cliente::count();
        $ventas = Venta::count();

        $ventasHoy = Venta::whereDate('created_at', Carbon::today())->count();

        $totalVendido = Venta::sum('total');

        $ventasMes = Venta::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $stockBajo = Producto::where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        $ultimasVentas = Venta::with('cliente')
            ->latest()
            ->take(5)
            ->get();

        $ultimos7Dias = collect();

        for ($i = 6; $i >= 0; $i--) {
            $fecha = Carbon::today()->subDays($i)->format('Y-m-d');
            $ultimos7Dias->push($fecha);
        }

        $ventasAgrupadas = Venta::select(
                DB::raw('DATE(created_at) as fecha'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('created_at', '>=', Carbon::today()->subDays(6))
            ->groupBy('fecha')
            ->orderBy('fecha', 'ASC')
            ->pluck('total', 'fecha');

        $ventasPorDia = $ultimos7Dias->map(function ($fecha) use ($ventasAgrupadas) {
            return [
                'fecha' => $fecha,
                'total' => $ventasAgrupadas[$fecha] ?? 0
            ];
        });

        $topProductos = DetalleVenta::select(
                'producto_id',
                DB::raw('SUM(cantidad) as total_vendidos')
            )
            ->with('producto')
            ->groupBy('producto_id')
            ->orderByDesc('total_vendidos')
            ->take(5)
            ->get();

        $mejorCliente = Venta::select(
                'cliente_id',
                DB::raw('COUNT(*) as total_compras'),
                DB::raw('SUM(total) as total_gastado')
            )
            ->with('cliente')
            ->whereNotNull('cliente_id')
            ->groupBy('cliente_id')
            ->orderByDesc('total_gastado')
            ->first();

        return view('dashboard', compact(
            'productos',
            'clientes',
            'ventas',
            'ventasHoy',
            'totalVendido',
            'ventasMes',
            'stockBajo',
            'ventasPorDia',
            'ultimasVentas',
            'topProductos',
            'mejorCliente'
        ));
    }
}