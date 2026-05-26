@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Gestión de Productos</h2>
            <p class="text-muted mb-0">Administra inventario, stock y categorías</p>
        </div>

        <a href="{{ route('productos.create') }}" class="btn btn-primary px-4">
            + Nuevo Producto
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4">

            <form method="GET" action="{{ route('productos.index') }}" class="row g-2 mb-4">
                <div class="col-md-7">
                    <input
                        type="text"
                        name="buscar"
                        class="form-control"
                        placeholder="Buscar por nombre, marca o SKU..."
                        value="{{ $buscar ?? '' }}"
                    >
                </div>

                <div class="col-md-3">
                    <select name="estado" class="form-select" onchange="this.form.submit()">
                        <option value="">Todos los productos</option>
                        <option value="bajo" {{ ($estado ?? '') == 'bajo' ? 'selected' : '' }}>
                            Bajo stock
                        </option>
                        <option value="agotado" {{ ($estado ?? '') == 'agotado' ? 'selected' : '' }}>
                            Agotados
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    @if(request('buscar') || request('estado'))
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary w-100">
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Producto</th>
                            <th>SKU</th>
                            <th>Categoría</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th width="180">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($productos as $producto)
                            <tr class="{{ $producto->stock == 0 ? 'table-danger' : ($producto->stock <= 5 ? 'table-warning' : '') }}">
                                <td>{{ $producto->id }}</td>

                                <td>
                                    <div class="fw-semibold">{{ $producto->nombre }}</div>

                                    <small class="text-muted d-block">
                                        {{ $producto->marca }}
                                    </small>

                                    @if($producto->descripcion)
                                        <small class="text-secondary d-block">
                                            {{ $producto->descripcion }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <span class="badge rounded-pill bg-secondary">
                                        {{ $producto->sku }}
                                    </span>
                                </td>

                                <td>
                                    @if($producto->categoria)
                                        <span class="badge rounded-pill bg-info text-dark">
                                            {{ $producto->categoria->nombre }}
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-light text-dark border">
                                            Sin categoría
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="fw-semibold">₡ {{ number_format($producto->precio, 2) }}</span>
                                </td>

                                <td>
                                    @if($producto->stock == 0)
                                        <span class="badge rounded-pill bg-danger">
                                            Agotado
                                        </span>
                                    @elseif($producto->stock <= 5)
                                        <span class="badge rounded-pill bg-warning text-dark">
                                            {{ $producto->stock }} - Bajo stock
                                        </span>
                                    @else
                                        <span class="badge rounded-pill bg-success">
                                            {{ $producto->stock }}
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form
                                        action="{{ route('productos.destroy', $producto) }}"
                                        method="POST"
                                        class="d-inline form-eliminar"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    No hay productos registrados
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $productos->links() }}
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

            Swal.fire({
                title: '¿Eliminar producto?',
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