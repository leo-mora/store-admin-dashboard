@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="section-title">👤 Nuevo Cliente</h2>
    <p class="section-subtitle">Ingrese los datos del cliente</p>
</div>

<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('clientes.store') }}" method="POST">
            @csrf

            @include('clientes.form')

            <div class="d-flex gap-2 mt-4">
                <button class="btn btn-primary px-4">
                    Guardar
                </button>

                <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary px-4">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

@endsection