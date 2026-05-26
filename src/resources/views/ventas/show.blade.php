@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="section-title">🧾 Venta #{{ $venta->id }}</h2>
    <p class="section-subtitle">Detalle completo de la venta registrada</p>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-2">
                <div class="text-muted small">Cliente</div>
                <div class="fw-semibold fs-5">{{ $venta->cliente?->nombre ?? 'Cliente eliminado' }}</div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Identificación</div>
                <div class="fw-semibold">{{ $venta->cliente?->identificacion ?? 'Sin registro' }}</div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Fecha</div>
                <div class="fw-semibold">{{ $venta->created_at->format('d/m/Y H:i:s') }}</div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Subtotal</div>
                <div class="fw-semibold">₡ {{ number_format($subtotal, 2) }}</div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Descuento</div>
                <div class="fw-semibold text-danger">
                    @if($venta->tipo_descuento === 'porcentaje')
                        {{ number_format($venta->valor_descuento, 2) }}% (-₡ {{ number_format($venta->descuento, 2) }})
                    @else
                        ₡ {{ number_format($venta->descuento, 2) }}
                    @endif
                </div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Total pagado</div>
                <div class="fw-bold fs-4 text-primary">₡ {{ number_format($venta->total, 2) }}</div>
            </div>

            <div class="col-md-6">
                <div class="text-muted small">Correo electrónico</div>
                <div class="fw-semibold">{{ $venta->cliente?->email ?? 'Sin correo' }}</div>
            </div>

            <div class="col-md-4">
                <div class="text-muted small">Teléfono</div>
                <div class="fw-semibold">{{ $venta->cliente?->telefono ?? 'Sin teléfono' }}</div>
            </div>

            <div class="col-md-2">
                <div class="text-muted small">Estado</div>
                <span class="badge bg-{{ $claseEstado }} fs-6">
                    {{ $estadoDevolucion }}
                </span>
            </div>

            <div class="col-12">
                <div class="text-muted small">Dirección</div>
                <div class="fw-semibold">{{ $venta->cliente?->direccion ?? 'Sin dirección registrada' }}</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="text-muted small">Total devuelto acumulado</div>
                <div class="fw-bold fs-3 text-danger">₡ {{ number_format($totalDevuelto, 2) }}</div>
            </div>

            <div class="col-md-4">
                <div class="text-muted small">Monto neto actual</div>
                <div class="fw-bold fs-3 text-success">₡ {{ number_format($montoNeto, 2) }}</div>
            </div>

            <div class="col-md-4 d-flex align-items-end justify-content-md-end">
                @if($montoNeto > 0)
                    <a href="{{ route('devoluciones.create', $venta->id) }}" class="btn btn-warning">
                        ↩️ Registrar devolución
                    </a>
                @else
                    <button class="btn btn-secondary" disabled>
                        Venta completamente devuelta
                    </button>
                @endif
            </div>
        </div>

        @if($montoNeto <= 0)
            <div class="alert alert-danger mt-4 mb-0">
                Esta venta fue devuelta completamente. La factura y el envío por correo están deshabilitados.
            </div>
        @endif
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Producto</th>
                        <th>SKU</th>
                        <th>Marca</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($venta->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto?->nombre ?? 'Producto eliminado' }}</td>
                            <td>{{ $detalle->producto?->sku ?? 'N/A' }}</td>
                            <td>{{ $detalle->producto?->marca ?? 'N/A' }}</td>
                            <td>₡ {{ number_format($detalle->precio, 2) }}</td>
                            <td>{{ $detalle->cantidad }}</td>
                            <td>₡ {{ number_format($detalle->precio * $detalle->cantidad, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Subtotal:</th>
                        <th>₡ {{ number_format($subtotal, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Descuento aplicado:</th>
                        <th class="text-danger">₡ {{ number_format($venta->descuento, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Total pagado:</th>
                        <th class="text-primary">₡ {{ number_format($venta->total, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Total devuelto:</th>
                        <th class="text-danger">₡ {{ number_format($totalDevuelto, 2) }}</th>
                    </tr>
                    <tr>
                        <th colspan="5" class="text-end">Monto neto actual:</th>
                        <th class="text-success">₡ {{ number_format($montoNeto, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-4 d-flex gap-2 flex-wrap">
            <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">
                Volver a ventas
            </a>

            @if($venta->cliente)
                <a href="{{ route('clientes.show', $venta->cliente->id) }}" class="btn btn-outline-primary">
                    Ver historial del cliente
                </a>
            @endif

            @if($montoNeto > 0)
                <a href="{{ route('ventas.factura', $venta->id) }}" class="btn btn-danger">
                    Descargar PDF
                </a>

                <form action="{{ route('ventas.enviarFactura', $venta->id) }}" method="POST" class="form-enviar-factura m-0">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        Enviar por correo
                    </button>
                </form>
            @else
                <button class="btn btn-danger" disabled>
                    Descargar PDF
                </button>

                <button class="btn btn-success" disabled>
                    Enviar por correo
                </button>
            @endif
        </div>
    </div>
</div>

@if($venta->devoluciones->count() > 0)
    <div class="card">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4">Historial de devoluciones</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Motivo</th>
                            <th>Total devuelto</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->devoluciones as $devolucion)
                            <tr>
                                <td>#{{ $devolucion->id }}</td>
                                <td>{{ $devolucion->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $devolucion->motivo ?: 'Sin motivo registrado' }}</td>
                                <td class="text-danger">₡ {{ number_format($devolucion->total_devuelto, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('devoluciones.show', $devolucion->id) }}" class="btn btn-sm btn-info text-white">
                                        Ver devolución
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@endsection