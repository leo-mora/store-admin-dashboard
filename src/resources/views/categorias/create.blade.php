@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="fw-bold mb-1">Nueva Categoría</h2>
                    <p class="text-muted mb-0">Registra una nueva categoría en el sistema</p>
                </div>

                <div class="card-body p-4">

                    @if ($errors->any())
                        <div class="alert alert-danger rounded-3">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="/categorias" method="POST">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Nombre de la categoría</label>
                            <input
                                type="text"
                                name="nombre"
                                class="form-control @error('nombre') is-invalid @enderror"
                                value="{{ old('nombre') }}"
                                placeholder="Ej: Electrónica"
                            >

                            @error('nombre')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                Guardar Categoría
                            </button>

                            <a href="/categorias" class="btn btn-outline-secondary px-4">
                                Volver
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection