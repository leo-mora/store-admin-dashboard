<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\DetalleVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacturaMail;
use App\Helpers\BitacoraHelper;

class VentaController extends Controller
{
    public function index(Request $request)
    {
        $buscar = trim($request->buscar ?? '');

        $ventas = Venta::with('cliente')
            ->when($buscar, function ($query, $buscar) {
                $query->whereHas('cliente', function ($q) use ($buscar) {
                    $q->where('nombre', 'like', "%{$buscar}%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ventas.index', compact('ventas'));
    }

    public function create()
    {
        $clientes = Cliente::orderBy('nombre')->get();
        $productos = Producto::orderBy('nombre')->get();

        return view('ventas.create', compact('clientes', 'productos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => ['required', 'exists:clientes,id'],
            'productos' => ['required'],
            'tipo_descuento' => ['nullable', 'in:monto,porcentaje'],
            'valor_descuento' => ['nullable', 'numeric', 'min:0'],
        ], [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists' => 'El cliente seleccionado no es válido.',
            'productos.required' => 'Debe agregar al menos un producto a la venta.',
            'tipo_descuento.in' => 'El tipo de descuento seleccionado no es válido.',
            'valor_descuento.numeric' => 'El valor del descuento debe ser un número válido.',
            'valor_descuento.min' => 'El valor del descuento no puede ser negativo.',
        ]);

        $productos = json_decode($request->productos, true);

        if (!is_array($productos) || count($productos) === 0) {
            return back()
                ->withInput()
                ->with('error', 'Debe agregar al menos un producto a la venta.');
        }

        $subtotal = 0;
        $tipoDescuento = $request->tipo_descuento ?? 'monto';
        $valorDescuento = (float) ($request->valor_descuento ?? 0);

        DB::beginTransaction();

        try {
            foreach ($productos as $item) {
                if (
                    !isset($item['id']) ||
                    !isset($item['cantidad']) ||
                    !is_numeric($item['cantidad']) ||
                    $item['cantidad'] < 1
                ) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with('error', 'Hay productos con cantidades inválidas.');
                }

                $producto = Producto::find($item['id']);

                if (!$producto) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with('error', 'Uno de los productos seleccionados no existe.');
                }

                if ($producto->stock < $item['cantidad']) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with('error', 'No hay suficiente stock para ' . $producto->nombre . '.');
                }

                $subtotal += $producto->precio * $item['cantidad'];
            }

            if ($tipoDescuento === 'porcentaje') {
                if ($valorDescuento > 100) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with('error', 'El porcentaje de descuento no puede ser mayor a 100.');
                }

                $descuentoCalculado = ($subtotal * $valorDescuento) / 100;
            } else {
                if ($valorDescuento > $subtotal) {
                    DB::rollBack();
                    return back()
                        ->withInput()
                        ->with('error', 'El descuento no puede ser mayor al subtotal de la venta.');
                }

                $descuentoCalculado = $valorDescuento;
            }

            $descuentoCalculado = round($descuentoCalculado, 2);
            $totalFinal = round($subtotal - $descuentoCalculado, 2);

            $venta = Venta::create([
                'cliente_id' => $request->cliente_id,
                'tipo_descuento' => $tipoDescuento,
                'valor_descuento' => $valorDescuento,
                'descuento' => $descuentoCalculado,
                'total' => $totalFinal,
            ]);

            foreach ($productos as $item) {
                $producto = Producto::find($item['id']);

                DetalleVenta::create([
                    'venta_id' => $venta->id,
                    'producto_id' => $producto->id,
                    'cantidad' => $item['cantidad'],
                    'precio' => $producto->precio,
                ]);

                $producto->decrement('stock', $item['cantidad']);
            }

            DB::commit();

            BitacoraHelper::registrar(
                'Ventas',
                'Crear',
                'Se registró la venta #' . $venta->id . ' por un total de ₡ ' . number_format($venta->total, 2)
            );

            return redirect()
                ->route('ventas.index')
                ->with('success', 'Venta registrada correctamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Ocurrió un error al registrar la venta.');
        }
    }

    public function show($id)
    {
        $venta = Venta::with([
            'cliente',
            'detalles.producto',
            'devoluciones'
        ])->findOrFail($id);

        $subtotal = $venta->detalles->sum(function ($detalle) {
            return $detalle->precio * $detalle->cantidad;
        });

        $totalDevuelto = $venta->devoluciones->sum('total_devuelto');
        $montoNeto = max($venta->total - $totalDevuelto, 0);

        if ($totalDevuelto <= 0) {
            $estadoDevolucion = 'Activa';
            $claseEstado = 'success';
        } elseif ($montoNeto <= 0.009) {
            $estadoDevolucion = 'Devuelta totalmente';
            $claseEstado = 'danger';
        } else {
            $estadoDevolucion = 'Parcialmente devuelta';
            $claseEstado = 'warning';
        }

        return view('ventas.show', compact(
            'venta',
            'subtotal',
            'totalDevuelto',
            'montoNeto',
            'estadoDevolucion',
            'claseEstado'
        ));
    }

    public function edit(Venta $venta)
    {
        //
    }

    public function update(Request $request, Venta $venta)
    {
        //
    }

    public function destroy(Venta $venta)
    {
        DB::beginTransaction();

        try {
            $ventaId = $venta->id;

            foreach ($venta->detalles as $detalle) {
                if ($detalle->producto) {
                    $detalle->producto->increment('stock', $detalle->cantidad);
                }
            }

            $venta->detalles()->delete();
            $venta->delete();

            DB::commit();

            BitacoraHelper::registrar(
                'Ventas',
                'Eliminar',
                'Se eliminó la venta #' . $ventaId . ' y se restauró el stock'
            );

            return redirect()
                ->route('ventas.index')
                ->with('success', 'Venta eliminada correctamente y el stock fue restaurado.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->route('ventas.index')
                ->with('error', 'No se pudo eliminar la venta.');
        }
    }

    public function factura($id)
{
    $venta = Venta::with([
        'cliente',
        'detalles.producto',
        'devoluciones'
    ])->findOrFail($id);

    $totalDevuelto = $venta->devoluciones->sum('total_devuelto');
    $montoNeto = max($venta->total - $totalDevuelto, 0);

    if ($montoNeto <= 0.009) {
        return redirect()
            ->route('ventas.show', $venta->id)
            ->with('error', 'No se puede generar la factura de una venta completamente devuelta.');
    }

    $subtotal = $venta->detalles->sum(function ($detalle) {
        return $detalle->precio * $detalle->cantidad;
    });

    BitacoraHelper::registrar(
        'Ventas',
        'Descargar factura',
        'Se descargó la factura de la venta #' . $venta->id
    );

    $pdf = Pdf::loadView('ventas.factura', compact('venta', 'subtotal'));

    return $pdf->download('factura_' . $venta->id . '.pdf');
}

    public function enviarFactura($id)
    {
        $venta = Venta::with([
            'cliente',
            'detalles.producto',
            'devoluciones'
        ])->findOrFail($id);

        $totalDevuelto = $venta->devoluciones->sum('total_devuelto');
        $montoNeto = max($venta->total - $totalDevuelto, 0);

        if ($montoNeto <= 0.009) {
            return redirect()
                ->route('ventas.show', $venta->id)
                ->with('error', 'No se puede enviar la factura de una venta completamente devuelta.');
        }

        if (!$venta->cliente || !$venta->cliente->email) {
            return back()->with('error', 'El cliente no tiene un correo registrado.');
        }

        Mail::to($venta->cliente->email)->send(new FacturaMail($venta));

        BitacoraHelper::registrar(
            'Ventas',
            'Enviar factura',
            'Se envió la factura de la venta #' . $venta->id . ' al correo ' . $venta->cliente->email
        );

        return back()->with('success', 'La factura fue enviada correctamente a ' . $venta->cliente->email . '.');
    }
}