@extends('layouts.app')

@section('content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h2 class="fw-bold mb-1">Nuevo Producto</h2>
                    <p class="text-muted mb-0">Registra un producto en el inventario</p>
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

                    <form action="/productos" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input
                                    type="text"
                                    name="nombre"
                                    class="form-control @error('nombre') is-invalid @enderror"
                                    value="{{ old('nombre') }}"
                                    placeholder="Ej: Refrigeradora Samsung 420L"
                                >
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Marca</label>
                                <input
                                    type="text"
                                    name="marca"
                                    class="form-control @error('marca') is-invalid @enderror"
                                    value="{{ old('marca') }}"
                                    placeholder="Ej: Samsung"
                                >
                                @error('marca')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">SKU</label>
                                <input
                                    type="text"
                                    class="form-control bg-light"
                                    value="Se generará automáticamente"
                                    readonly
                                >
                                <div class="form-text">
                                    El sistema generará el código SKU automáticamente.
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Precio</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="precio"
                                    class="form-control @error('precio') is-invalid @enderror"
                                    value="{{ old('precio') }}"
                                    placeholder="Ej: 850000"
                                >
                                @error('precio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Stock inicial</label>
                                <input
                                    type="number"
                                    min="0"
                                    name="stock"
                                    class="form-control @error('stock') is-invalid @enderror"
                                    value="{{ old('stock') }}"
                                    placeholder="Ej: 10"
                                >
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Categoría</label>
                                <select
                                    name="categoria_id"
                                    class="form-select @error('categoria_id') is-invalid @enderror"
                                >
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                            {{ $categoria->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('categoria_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea
                                    name="descripcion"
                                    rows="4"
                                    class="form-control @error('descripcion') is-invalid @enderror"
                                    placeholder="Describe brevemente el producto"
                                >{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button class="btn btn-primary px-4">
                                Guardar Producto
                            </button>

                            <a href="/productos" class="btn btn-outline-secondary px-4">
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