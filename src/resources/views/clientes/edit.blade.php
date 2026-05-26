@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="section-title">✏️ Editar Cliente</h2>
    <p class="section-subtitle">Modifique la información del cliente</p>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('clientes.form')

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary px-4">
                    Actualizar
                </button>

                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection