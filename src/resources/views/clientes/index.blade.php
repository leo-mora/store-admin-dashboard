@extends('layouts.app')

@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">👤 Clientes</h2>
        <p class="section-subtitle">Administre los clientes registrados</p>
    </div>

    <a href="{{ route('clientes.create') }}" class="btn btn-primary px-4">
        + Nuevo Cliente
    </a>
</div>

<div class="card mb-4">
    <div class="card-body p-4">

        <form method="GET" action="{{ route('clientes.index') }}">
            <div class="row g-3 align-items-center">

                <div class="col-md-8">
                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por nombre, identificación, email o teléfono..."
                        value="{{ request('buscar') }}"
                    >
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary px-4">
                        Buscar
                    </button>

                    @if(request('buscar'))
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">
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
            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Cliente</th>
                        <th>Contacto</th>
                        <th>Dirección</th>
                        <th>Compras</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($clientes as $cliente)
                        <tr>
                            <td>#{{ $cliente->id }}</td>

                            <td>
                                <div class="fw-semibold">{{ $cliente->nombre }}</div>
                                <small class="text-muted">ID: {{ $cliente->identificacion }}</small>
                            </td>

                            <td>
                                <div>{{ $cliente->email }}</div>
                                <small class="text-muted">{{ $cliente->telefono }}</small>
                            </td>

                            <td>
                                <small class="text-muted">{{ $cliente->direccion }}</small>
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $cliente->ventas_count }} compra(s)
                                </span>
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">

                                    <a href="{{ route('clientes.show', $cliente->id) }}" class="btn btn-sm btn-info text-white">
                                        Historial
                                    </a>

                                    <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    <form
                                        action="{{ route('clientes.destroy', $cliente->id) }}"
                                        method="POST"
                                        class="form-eliminar m-0"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger">
                                            Eliminar
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                No hay clientes registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-4">
            {{ $clientes->links() }}
        </div>

    </div>
</div>

@endsection