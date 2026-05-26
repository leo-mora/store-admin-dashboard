@extends('layouts.app')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="section-title">💰 Ventas</h2>
        <p class="section-subtitle">Consulte y administre las ventas registradas</p>
    </div>

    <a href="{{ route('ventas.create') }}" class="btn btn-primary px-4">
        + Nueva Venta
    </a>
</div>

<div class="card">
    <div class="card-body p-4">

        <form method="GET" action="{{ route('ventas.index') }}" class="row g-3 mb-4">
            <div class="col-md-6">
                <input
                    type="text"
                    name="buscar"
                    class="form-control"
                    placeholder="Buscar por nombre del cliente..."
                    value="{{ request('buscar') }}"
                >
            </div>

            <div class="col-md-auto">
                <button class="btn btn-outline-primary">
                    Buscar
                </button>
            </div>

            @if(request('buscar'))
                <div class="col-md-auto">
                    <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">
                        Limpiar
                    </a>
                </div>
            @endif
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Total</th>
                        <th>Fecha</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($ventas as $venta)
                        <tr>
                            <td>{{ $venta->id }}</td>

                            <td>
                                <strong>{{ $venta->cliente?->nombre ?? 'Cliente eliminado' }}</strong>
                            </td>

                            <td>
                                <span class="badge bg-success px-3 py-2">
                                    ₡ {{ number_format($venta->total, 2) }}
                                </span>

                                @if($venta->descuento > 0)
                                    <div class="text-danger small mt-1">
                                        Descuento: -₡ {{ number_format($venta->descuento, 2) }}
                                    </div>
                                @endif
                            </td>

                            <td>{{ $venta->created_at->format('d/m/Y H:i') }}</td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('ventas.show', $venta->id) }}" class="btn btn-info btn-sm text-white">
                                        Ver
                                    </a>

                                    <form action="{{ route('ventas.destroy', $venta->id) }}" method="POST" class="form-eliminar m-0">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No hay ventas registradas.
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

@endsection