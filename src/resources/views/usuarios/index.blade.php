@extends('layouts.app')

@section('content')

<div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h2 class="section-title">👥 Usuarios</h2>
        <p class="section-subtitle">Administre los usuarios del sistema</p>
    </div>

    <a href="{{ route('usuarios.create') }}" class="btn btn-primary px-4">
        + Nuevo Usuario
    </a>
</div>

<div class="card">
    <div class="card-body p-4">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">

                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Rol</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($usuarios as $u)
                        <tr>
                            <td>#{{ $u->id }}</td>

                            <td>
                                <strong>{{ $u->name }}</strong>
                            </td>

                            <td>{{ $u->email }}</td>

                            <td>
                                @if($u->rol === 'admin')
                                    <span class="badge bg-primary px-3 py-2">Administrador</span>
                                @else
                                    <span class="badge bg-secondary px-3 py-2">Empleado</span>
                                @endif
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2 flex-wrap">
                                    <a href="{{ route('usuarios.edit', $u->id) }}" class="btn btn-warning btn-sm">
                                        Editar
                                    </a>

                                    <form action="{{ route('usuarios.destroy', $u->id) }}" method="POST" class="form-eliminar d-inline m-0">
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
                                No hay usuarios registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</div>

@endsection