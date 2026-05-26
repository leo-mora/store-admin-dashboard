@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="section-title">👤 Nuevo Usuario</h2>
    <p class="section-subtitle">Complete la información para crear un usuario</p>
</div>

<div class="card">
    <div class="card-body p-4">

        <form action="{{ route('usuarios.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre</label>
                <input
                    type="text"
                    name="name"
                    class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}"
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
                    value="{{ old('email') }}"
                    placeholder="correo@ejemplo.com"
                >
                @error('email')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Contraseña</label>
                <input
                    type="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="Mínimo 6 caracteres"
                >
                @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Rol</label>
                <select name="rol" class="form-select @error('rol') is-invalid @enderror">
                    <option value="">Seleccione un rol</option>
                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="empleado" {{ old('rol') == 'empleado' ? 'selected' : '' }}>Empleado</option>
                </select>
                @error('rol')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    Guardar Usuario
                </button>

                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
            </div>

        </form>

    </div>
</div>

@endsection