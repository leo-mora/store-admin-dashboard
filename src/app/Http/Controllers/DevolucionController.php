<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Producto;
use App\Models\Devolucion;
use App\Models\DetalleDevolucion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\BitacoraHelper;

class DevolucionController extends Controller
{
    public function create($ventaId)
    {
        $venta = Venta::with([
            'cliente',
            'detalles.producto',
            'devoluciones.detalles'
        ])->findOrFail($ventaId);

        $cantidadesDevueltas = [];
        $subtotalesDevueltos = [];

        foreach ($venta->devoluciones as $devolucion) {
            foreach ($devolucion->detalles as $detalle) {
                if (!isset($cantidadesDevueltas[$detalle->producto_id])) {
                    $cantidadesDevueltas[$detalle->producto_id] = 0;
                }

                if (!isset($subtotalesDevueltos[$detalle->producto_id])) {
                    $subtotalesDevueltos[$detalle->producto_id] = 0;
                }

                $cantidadesDevueltas[$detalle->producto_id] += $detalle->cantidad;
                $subtotalesDevueltos[$detalle->producto_id] += $detalle->subtotal;
            }
        }

        $subtotalVenta = $venta->detalles->sum(function ($detalle) {
            return $detalle->precio * $detalle->cantidad;
        });

        $lineas = $this->calcularLineasNetas($venta, $subtotalVenta);

        return view('devoluciones.create', compact(
            'venta',
            'cantidadesDevueltas',
            'subtotalesDevueltos',
            'subtotalVenta',
            'lineas'
        ));
    }

    public function store(Request $request, $ventaId)
    {
        $venta = Venta::with([
            'detalles.producto',
            'devoluciones.detalles'
        ])->findOrFail($ventaId);

        $request->validate([
            'motivo' => ['nullable', 'string', 'max:1000'],
            'cantidades' => ['required', 'array'],
        ], [
            'cantidades.required' => 'Debe seleccionar al menos un producto para devolver.',
        ]);

        $cantidadesSolicitadas = $request->cantidades ?? [];

        $cantidadesDevueltas = [];
        $subtotalesDevueltos = [];

        foreach ($venta->devoluciones as $devolucionExistente) {
            foreach ($devolucionExistente->detalles as $detalleDevuelto) {
                if (!isset($cantidadesDevueltas[$detalleDevuelto->producto_id])) {
                    $cantidadesDevueltas[$detalleDevuelto->producto_id] = 0;
                }

                if (!isset($subtotalesDevueltos[$detalleDevuelto->producto_id])) {
                    $subtotalesDevueltos[$detalleDevuelto->producto_id] = 0;
                }

                $cantidadesDevueltas[$detalleDevuelto->producto_id] += $detalleDevuelto->cantidad;
                $subtotalesDevueltos[$detalleDevuelto->producto_id] += $detalleDevuelto->subtotal;
            }
        }

        $subtotalVenta = $venta->detalles->sum(function ($detalle) {
            return $detalle->precio * $detalle->cantidad;
        });

        $lineas = $this->calcularLineasNetas($venta, $subtotalVenta);

        $itemsADevolver = [];
        $totalDevuelto = 0;

        foreach ($venta->detalles as $detalleVenta) {
            $productoId = $detalleVenta->producto_id;
            $cantidadSolicitada = (int) ($cantidadesSolicitadas[$productoId] ?? 0);

            $cantidadYaDevuelta = $cantidadesDevueltas[$productoId] ?? 0;
            $cantidadDisponibleParaDevolver = $detalleVenta->cantidad - $cantidadYaDevuelta;

            if ($cantidadSolicitada < 0) {
                return back()
                    ->withInput()
                    ->with('error', 'Hay cantidades inválidas en la devolución.');
            }

            if ($cantidadSolicitada > $cantidadDisponibleParaDevolver) {
                return back()
                    ->withInput()
                    ->with('error', 'No puede devolver más unidades de las permitidas para el producto: ' . ($detalleVenta->producto?->nombre ?? 'Producto'));
            }

            if ($cantidadSolicitada > 0) {
                $linea = $lineas[$productoId];
                $subtotalYaDevuelto = $subtotalesDevueltos[$productoId] ?? 0;

                if ($cantidadSolicitada === $cantidadDisponibleParaDevolver) {
                    $subtotalDevolucion = round($linea['neto_total'] - $subtotalYaDevuelto, 2);
                } else {
                    $subtotalDevolucion = round(($linea['neto_total'] / $linea['cantidad_total']) * $cantidadSolicitada, 2);
                }

                if ($subtotalDevolucion < 0) {
                    $subtotalDevolucion = 0;
                }

                $precioUnitarioDevuelto = $cantidadSolicitada > 0
                    ? round($subtotalDevolucion / $cantidadSolicitada, 2)
                    : 0;

                $itemsADevolver[] = [
                    'producto_id' => $productoId,
                    'cantidad' => $cantidadSolicitada,
                    'precio' => $precioUnitarioDevuelto,
                    'subtotal' => $subtotalDevolucion,
                ];

                $totalDevuelto += $subtotalDevolucion;
            }
        }

        if (count($itemsADevolver) === 0) {
            return back()
                ->withInput()
                ->with('error', 'Debe indicar al menos una cantidad mayor a cero para devolver.');
        }

        DB::beginTransaction();

        try {
            $devolucion = Devolucion::create([
                'venta_id' => $venta->id,
                'motivo' => $request->motivo,
                'total_devuelto' => round($totalDevuelto, 2),
            ]);

            foreach ($itemsADevolver as $item) {
                DetalleDevolucion::create([
                    'devolucion_id' => $devolucion->id,
                    'producto_id' => $item['producto_id'],
                    'cantidad' => $item['cantidad'],
                    'precio' => $item['precio'],
                    'subtotal' => $item['subtotal'],
                ]);

                $producto = Producto::find($item['producto_id']);

                if ($producto) {
                    $producto->increment('stock', $item['cantidad']);
                }
            }

            DB::commit();

            BitacoraHelper::registrar(
                'Devoluciones',
                'Crear',
                'Se registró la devolución #' . $devolucion->id . ' de la venta #' . $venta->id . ' por un total de ₡ ' . number_format($devolucion->total_devuelto, 2)
            );

            return redirect()
                ->route('devoluciones.show', $devolucion->id)
                ->with('success', 'Devolución registrada correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar la devolución.');
        }
    }

    public function show($id)
    {
        $devolucion = Devolucion::with([
            'venta.cliente',
            'detalles.producto'
        ])->findOrFail($id);

        return view('devoluciones.show', compact('devolucion'));
    }

    private function calcularLineasNetas(Venta $venta, float $subtotalVenta): array
    {
        $lineas = [];
        $detalles = $venta->detalles->values();
        $ultimaPosicion = $detalles->count() - 1;

        $descuentoAcumulado = 0;
        $netoAcumulado = 0;

        foreach ($detalles as $index => $detalle) {
            $lineaBruta = $detalle->precio * $detalle->cantidad;

            if ($subtotalVenta <= 0) {
                $descuentoLinea = 0;
                $netoLinea = 0;
            } else {
                if ($venta->tipo_descuento === 'porcentaje') {
                    $descuentoLinea = round($lineaBruta * ($venta->valor_descuento / 100), 2);
                } else {
                    if ($index === $ultimaPosicion) {
                        $descuentoLinea = round($venta->descuento - $descuentoAcumulado, 2);
                    } else {
                        $descuentoLinea = round(($lineaBruta / $subtotalVenta) * $venta->descuento, 2);
                    }
                }

                if ($index === $ultimaPosicion) {
                    $netoLinea = round($venta->total - $netoAcumulado, 2);
                } else {
                    $netoLinea = round($lineaBruta - $descuentoLinea, 2);
                }
            }

            $descuentoAcumulado += $descuentoLinea;
            $netoAcumulado += $netoLinea;

            $lineas[$detalle->producto_id] = [
                'cantidad_total' => $detalle->cantidad,
                'precio_original' => $detalle->precio,
                'subtotal_bruto' => round($lineaBruta, 2),
                'descuento_linea' => round($descuentoLinea, 2),
                'neto_total' => round($netoLinea, 2),
            ];
        }

        return $lineas;
    }
}