@extends('layouts.app')

@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">↩️ Nueva Devolución</h2>
        <p class="section-subtitle">Registre productos devueltos de la venta #{{ $venta->id }}</p>
    </div>

    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-outline-secondary">
        Volver a la venta
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-muted small">Venta</div>
                <div class="fw-semibold fs-5">#{{ $venta->id }}</div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Cliente</div>
                <div class="fw-semibold fs-5">{{ $venta->cliente?->nombre ?? 'Cliente eliminado' }}</div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Fecha</div>
                <div class="fw-semibold">{{ $venta->created_at->format('d/m/Y H:i:s') }}</div>
            </div>

            <div class="col-md-3">
                <div class="text-muted small">Total pagado en la venta</div>
                <div class="fw-bold fs-4 text-primary">₡ {{ number_format($venta->total, 2) }}</div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('devoluciones.store', $venta->id) }}" method="POST">
    @csrf

    <div class="card mb-4">
        <div class="card-body p-4">
            <label class="form-label fw-semibold">Motivo de la devolución</label>
            <textarea
                name="motivo"
                rows="3"
                class="form-control @error('motivo') is-invalid @enderror"
                placeholder="Ejemplo: producto defectuoso, cliente cambió de opinión, error de entrega..."
            >{{ old('motivo') }}</textarea>

            @error('motivo')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>

    <div class="card">
        <div class="card-body p-4">
            <h5 class="fw-semibold mb-4">Productos vendidos</h5>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Producto</th>
                            <th>Vendidos</th>
                            <th>Ya devueltos</th>
                            <th>Máximo disponible</th>
                            <th>Precio original</th>
                            <th>Neto por línea</th>
                            <th>Cantidad a devolver</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $detalle)
                            @php
                                $yaDevueltos = $cantidadesDevueltas[$detalle->producto_id] ?? 0;
                                $maximo = $detalle->cantidad - $yaDevueltos;
                                $linea = $lineas[$detalle->producto_id];
                            @endphp

                            <tr>
                                <td>{{ $detalle->producto?->nombre ?? 'Producto eliminado' }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ $yaDevueltos }}</td>
                                <td>
                                    <span class="badge {{ $maximo > 0 ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $maximo }}
                                    </span>
                                </td>
                                <td>₡ {{ number_format($detalle->precio, 2) }}</td>
                                <td>₡ {{ number_format($linea['neto_total'], 2) }}</td>
                                <td style="max-width: 150px;">
                                    <input
                                        type="number"
                                        name="cantidades[{{ $detalle->producto_id }}]"
                                        class="form-control"
                                        min="0"
                                        max="{{ $maximo }}"
                                        value="{{ old('cantidades.' . $detalle->producto_id, 0) }}"
                                        {{ $maximo <= 0 ? 'disabled' : '' }}
                                    >
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="alert alert-info mt-4 mb-0">
                El monto devuelto se calculará con base en lo que realmente pagó el cliente, incluyendo el descuento aplicado en la venta.
            </div>

            <div class="mt-4 d-flex gap-2 flex-wrap">
                <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-outline-secondary">
                    Cancelar
                </a>

                <button type="submit" class="btn btn-primary">
                    Registrar devolución
                </button>
            </div>
        </div>
    </div>
</form>

@endsection