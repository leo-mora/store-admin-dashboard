@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="section-title">✏️ Editar Usuario</h2>
    <p class="section-subtitle">Actualice la información del usuario</p>
</div>

<div class="card">
    <div class="card-body p-4">

        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre</label>
                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $usuario->name) }}"
                    placeholder="Ingrese el nombre"
                >
                @error('name')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Correo electrónico</label>
                <input
                    type="email"
                    name="email"
                    class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $usuario->email) }}"
                    placeholder="correo@ejemplo.com"
                >
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Nueva contraseña</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Déjela vacía si no desea cambiarla"
                >
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <small class="text-muted">Solo llénela si desea actualizar la contraseña.</small>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Rol</label>
                <select name="rol" class="form-select @error('rol') is-invalid @enderror">
                    <option value="admin" {{ old('rol', $usuario->rol) == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="empleado" {{ old('rol', $usuario->rol) == 'empleado' ? 'selected' : '' }}>Empleado</option>
                </select>
                @error('rol')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    Actualizar Usuario
                </button>

                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>

@endsection