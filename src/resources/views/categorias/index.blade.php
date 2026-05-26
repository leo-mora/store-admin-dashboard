@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestión de Categorías</h2>
            <p class="text-muted mb-0">Administra las categorías del sistema</p>
        </div>

        <a href="/categorias/create" class="btn btn-primary px-4">
            + Nueva Categoría
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form method="GET" action="/categorias" class="row g-2 mb-4">
                <div class="col-md-6">
                    <input
                        type="text"
                        name="buscar"
                        value="{{ $buscar ?? '' }}"
                        class="form-control"
                        placeholder="Buscar categoría..."
                    >
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100">Buscar</button>
                </div>

                <div class="col-md-2">
@if(request('buscar'))
    <a href="{{ route('categorias.index') }}" class="btn btn-outline-secondary">
        Limpiar
    </a>
@endif                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Productos</th>
                            <th style="width: 190px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categorias as $categoria)
                            <tr>
                                <td>{{ $categoria->id }}</td>
                                <td>
                                    <span class="fw-semibold">{{ $categoria->nombre }}</span>
                                </td>
                                <td>
                                    <span class="badge rounded-pill {{ $categoria->productos_count > 0 ? 'bg-info text-dark' : 'bg-light text-dark border' }}">
                                        {{ $categoria->productos_count }}
                                    </span>
                                </td>
                                <td>
                                    <a href="/categorias/{{ $categoria->id }}/edit" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="/categorias/{{ $categoria->id }}" method="POST" class="d-inline form-eliminar">
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm"
                                            {{ $categoria->productos_count > 0 ? 'disabled' : '' }}
                                            title="{{ $categoria->productos_count > 0 ? 'No se puede eliminar porque tiene productos asociados' : 'Eliminar categoría' }}"
                                        >
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No se encontraron categorías.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('success'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: @json(session('success')),
        showConfirmButton: false,
        timer: 2500,
        timerProgressBar: true
    });
</script>
@endif

@if(session('error'))
<script>
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: @json(session('error')),
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
</script>
@endif

<script>
    document.querySelectorAll('.form-eliminar').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const boton = form.querySelector('button');

            if (boton.hasAttribute('disabled')) {
                return;
            }

            Swal.fire({
                title: '¿Eliminar categoría?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>

@endsection