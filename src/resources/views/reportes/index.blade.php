@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="section-title">📊 Reportes de Ventas</h2>
    <p class="section-subtitle">Consulte las ventas por rango de fechas y cliente</p>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('reportes.index') }}" id="formReportes">
            <div class="row g-3 align-items-end">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha inicio</label>
                    <input
                        type="date"
                        name="fecha_inicio"
                        id="fecha_inicio"
                        class="form-control @error('fecha_inicio') is-invalid @enderror"
                        value="{{ $fecha_inicio }}"
                    >
                    @error('fecha_inicio')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Fecha fin</label>
                    <input
                        type="date"
                        name="fecha_fin"
                        id="fecha_fin"
                        class="form-control @error('fecha_fin') is-invalid @enderror"
                        value="{{ $fecha_fin }}"
                    >
                    @error('fecha_fin')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Buscar cliente</label>
                    <input
                        type="text"
                        name="cliente"
                        class="form-control @error('cliente') is-invalid @enderror"
                        placeholder="Nombre del cliente..."
                        value="{{ $cliente }}"
                    >
                    @error('cliente')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="col-12 d-flex gap-2 flex-wrap">
                    <button class="btn btn-primary px-4">
                        Filtrar Reporte
                    </button>

                    <a
                        href="{{ route('reportes.excel', ['fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin, 'cliente' => $cliente]) }}"
                        class="btn btn-success px-4"
                    >
                        Exportar Excel
                    </a>

                    @if($fecha_inicio || $fecha_fin || $cliente)
                        <a href="{{ route('reportes.index') }}" class="btn btn-outline-secondary px-4">
                            Limpiar
                        </a>
                    @endif
                </div>

            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">

    <div class="col-md-6 col-xl-4">
        <div class="card text-white h-100 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #0d6efd, #3d8bfd);">
            <div class="card-body text-center py-4 px-3">
                <div class="mb-2 fw-semibold fs-5">Ventas</div>
                <div class="fw-bold display-6">{{ $totalVentas }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card text-white h-100 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #198754, #28a745);">
            <div class="card-body text-center py-4 px-3">
                <div class="mb-2 fw-semibold fs-5">Total Vendido</div>
                <div class="fw-bold fs-1" style="line-height: 1.2;">
                    ₡ {{ number_format($totalVendido, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card text-white h-100 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #f0ad00, #ffc107);">
            <div class="card-body text-center py-4 px-3">
                <div class="mb-2 fw-semibold fs-5">Clientes</div>
                <div class="fw-bold display-6">{{ $totalClientes }}</div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card text-white h-100 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #dc3545, #e35d6a);">
            <div class="card-body text-center py-4 px-3">
                <div class="mb-2 fw-semibold fs-5">Total Devuelto</div>
                <div class="fw-bold fs-1" style="line-height: 1.2;">
                    ₡ {{ number_format($totalDevuelto, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card text-white h-100 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #6f42c1, #8a63d2);">
            <div class="card-body text-center py-4 px-3">
                <div class="mb-2 fw-semibold fs-5">Ventas Netas</div>
                <div class="fw-bold fs-1" style="line-height: 1.2;">
                    ₡ {{ number_format($ventasNetas, 2) }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-4">
        <div class="card text-white h-100 border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #20c997, #3dd5b0);">
            <div class="card-body text-center py-4 px-3">
                <div class="mb-2 fw-semibold fs-5">Devoluciones</div>
                <div class="fw-bold display-6">{{ $cantidadDevoluciones }}</div>
            </div>
        </div>
    </div>

</div>

<div class="card">
    <div class="card-body p-4">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total Venta</th>
                        <th>Total Devuelto</th>
                        <th>Neto</th>
                        <th>Fecha</th>
                        <th class="text-center">Acción</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ventas as $venta)
                        @php
                            $devuelto = $venta->devoluciones->sum('total_devuelto');
                            $neto = $venta->total - $devuelto;
                        @endphp

                        <tr>
                            <td>#{{ $venta->id }}</td>

                            <td>
                                <strong>{{ $venta->cliente?->nombre ?? 'Sin cliente' }}</strong>
                            </td>

                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    ₡ {{ number_format($venta->total, 2) }}
                                </span>
                            </td>

                            <td>
                                @if($devuelto > 0)
                                    <span class="badge bg-danger px-3 py-2">
                                        ₡ {{ number_format($devuelto, 2) }}
                                    </span>
                                @else
                                    <span class="text-muted">₡ 0.00</span>
                                @endif
                            </td>

                            <td>
                                <span class="badge bg-primary px-3 py-2">
                                    ₡ {{ number_format($neto, 2) }}
                                </span>
                            </td>

                            <td>
                                {{ $venta->created_at->format('d/m/Y H:i') }}
                            </td>

                            <td class="text-center">
                                <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-sm btn-outline-primary">
                                    Ver
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No hay ventas registradas para ese filtro.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        @if($ventas->hasPages())
            <div class="mt-4">
                {{ $ventas->links() }}
            </div>
        @endif

    </div>
</div>

<script>
    const formReportes = document.getElementById('formReportes');
    const fechaInicio = document.getElementById('fecha_inicio');
    const fechaFin = document.getElementById('fecha_fin');

    function enviarSiFechasValidas() {
        const f1 = fechaInicio.value;
        const f2 = fechaFin.value;

        if ((f1 && f2) || (!f1 && !f2)) {
            formReportes.submit();
        }
    }

    fechaInicio.addEventListener('change', enviarSiFechasValidas);
    fechaFin.addEventListener('change', enviarSiFechasValidas);
</script>

@endsection