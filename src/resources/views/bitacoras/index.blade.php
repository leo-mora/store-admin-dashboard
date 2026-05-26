@extends('layouts.app')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="section-title">📋 Bitácora del sistema</h2>
        <p class="section-subtitle">Consulte las acciones realizadas por los usuarios</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body p-4">
        <form method="GET" action="{{ route('bitacoras.index') }}">
            <div class="row g-3 align-items-center">
                <div class="col-md-8">
                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por usuario, módulo, acción o descripción..."
                        value="{{ request('buscar') }}"
                    >
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary px-4">
                        Buscar
                    </button>

                    @if(request('buscar'))
                        <a href="{{ route('bitacoras.index') }}" class="btn btn-outline-secondary">
                            Limpiar
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Módulo</th>
                        <th>Acción</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bitacoras as $bitacora)
                        <tr>
                            <td>#{{ $bitacora->id }}</td>
                            <td>
                                <div class="fw-semibold">
                                    {{ $bitacora->usuario?->name ?? 'Usuario eliminado' }}
                                </div>
                                <small class="text-muted">
                                    {{ $bitacora->usuario?->email ?? 'Sin correo' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-primary">
                                    {{ $bitacora->modulo }}
                                </span>
                            </td>
                            <td>{{ $bitacora->accion }}</td>
                            <td>{{ $bitacora->descripcion }}</td>
                            <td>{{ $bitacora->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay registros en la bitácora.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bitacoras->hasPages())
            <div class="mt-4">
                {{ $bitacoras->links() }}
            </div>
        @endif
    </div>
</div>

@endsection