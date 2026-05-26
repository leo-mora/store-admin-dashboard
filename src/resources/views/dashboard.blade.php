@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="section-title">📊 Panel de Control</h2>
    <p class="section-subtitle">Resumen general del sistema de ventas</p>
</div>

<div class="row g-4 mb-4">

    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="text-muted mb-2 fw-semibold">Productos</div>
                <h2 class="text-primary fw-bold mb-0">{{ $productos }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="text-muted mb-2 fw-semibold">Clientes</div>
                <h2 class="text-success fw-bold mb-0">{{ $clientes }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="text-muted mb-2 fw-semibold">Ventas Totales</div>
                <h2 class="text-warning fw-bold mb-0">{{ $ventas }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="text-muted mb-2 fw-semibold">Ventas Hoy</div>
                <h2 class="text-danger fw-bold mb-0">{{ $ventasHoy }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="text-muted mb-2 fw-semibold">Total Vendido</div>
                <h2 class="text-primary fw-bold mb-0">₡ {{ number_format($totalVendido, 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-body text-center p-4">
                <div class="text-muted mb-2 fw-semibold">Ventas del Mes</div>
                <h2 class="text-success fw-bold mb-0">{{ $ventasMes }}</h2>
            </div>
        </div>
    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="mb-1 fw-semibold">Ventas de los últimos 7 días</h5>
                <p class="text-muted mb-0 small">Cantidad de ventas registradas por día</p>
            </div>

            <div class="card-body px-4 pb-4">
                <canvas id="graficaVentas" height="110"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        @if($stockBajo->count() > 0)
            <div class="card h-100 border-0">
                <div class="card-header bg-danger text-white border-0 pt-4 px-4">
                    <h5 class="mb-1 fw-semibold">⚠ Stock Bajo</h5>
                    <p class="mb-0 small">Productos con stock menor o igual a 5</p>
                </div>

                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-sm align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-end">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($stockBajo as $producto)
                                    <tr>
                                        <td>{{ $producto->nombre }}</td>
                                        <td class="text-end">
                                            @if($producto->stock == 0)
                                                <span class="badge bg-danger">Agotado</span>
                                            @else
                                                <span class="badge bg-warning text-dark">{{ $producto->stock }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @else
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-center align-items-center text-center p-4">
                    <h5 class="text-success fw-semibold mb-2">✔ Inventario estable</h5>
                    <p class="text-muted mb-0">No hay productos con stock bajo en este momento.</p>
                </div>
            </div>
        @endif
    </div>

</div>

<div class="row g-4 mb-4">

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="mb-1 fw-semibold">🏆 Mejor Cliente</h5>
                <p class="text-muted mb-0 small">Cliente con mayor monto comprado</p>
            </div>

            <div class="card-body px-4 pb-4">
                @if($mejorCliente && $mejorCliente->cliente)
                    <div class="mb-2 fw-bold fs-5">{{ $mejorCliente->cliente->nombre }}</div>
                    <div class="text-muted mb-2">Compras realizadas: {{ $mejorCliente->total_compras }}</div>
                    <div class="badge bg-success px-3 py-2">
                        ₡ {{ number_format($mejorCliente->total_gastado, 2) }}
                    </div>
                @else
                    <p class="text-muted mb-0">Aún no hay suficiente información.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="mb-1 fw-semibold">🔥 Top 5 Productos</h5>
                <p class="text-muted mb-0 small">Productos más vendidos</p>
            </div>

            <div class="card-body px-4 pb-4">
                @if($topProductos->count() > 0)
                    <ul class="list-group list-group-flush">
                        @foreach($topProductos as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>{{ $item->producto->nombre ?? 'Producto eliminado' }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $item->total_vendidos }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No hay productos vendidos todavía.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="mb-1 fw-semibold">⚡ Accesos Rápidos</h5>
                <p class="text-muted mb-0 small">Ir directamente a módulos principales</p>
            </div>

            <div class="card-body px-4 pb-4 d-grid gap-2">
                <a href="{{ route('ventas.create') }}" class="btn btn-primary">Nueva Venta</a>
                <a href="{{ route('productos.index') }}" class="btn btn-outline-primary">Ver Productos</a>
                <a href="{{ route('clientes.index') }}" class="btn btn-outline-success">Ver Clientes</a>
                <a href="{{ route('reportes.index') }}" class="btn btn-outline-dark">Ver Reportes</a>
            </div>
        </div>
    </div>

</div>

<div class="card mt-4">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="mb-1 fw-semibold">🧾 Últimas ventas</h5>
        <p class="text-muted mb-0 small">Ventas registradas recientemente</p>
    </div>

    <div class="card-body px-4 pb-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ultimasVentas as $venta)
                        <tr>
                            <td>#{{ $venta->id }}</td>
                            <td>
                                <strong>{{ $venta->cliente->nombre ?? 'Cliente general' }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    ₡ {{ number_format($venta->total, 2) }}
                                </span>
                            </td>
                            <td>{{ $venta->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                                No hay ventas registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const labels = [
        @foreach($ventasPorDia as $venta)
            "{{ \Carbon\Carbon::parse($venta['fecha'])->format('d/m') }}",
        @endforeach
    ];

    const data = [
        @foreach($ventasPorDia as $venta)
            {{ $venta['total'] }},
        @endforeach
    ];

    const ctx = document.getElementById('graficaVentas').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Ventas por día',
                data: data,
                fill: true,
                tension: 0.35,
                borderWidth: 3,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: true
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });
</script>

@endsection