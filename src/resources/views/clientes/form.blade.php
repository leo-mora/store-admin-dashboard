<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Nombre completo</label>
        <input
            type="text"
            name="nombre"
            class="form-control @error('nombre') is-invalid @enderror"
            value="{{ old('nombre', $cliente->nombre ?? '') }}"
            placeholder="Ej: Leonardo Mora"
        >
        @error('nombre')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Identificación</label>
        <input
            type="text"
            name="identificacion"
            class="form-control @error('identificacion') is-invalid @enderror"
            value="{{ old('identificacion', $cliente->identificacion ?? '') }}"
            placeholder="Ej: 123456789"
        >
        @error('identificacion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Teléfono</label>
        <input
            type="text"
            name="telefono"
            class="form-control @error('telefono') is-invalid @enderror"
            value="{{ old('telefono', $cliente->telefono ?? '') }}"
            placeholder="Ej: 88881111"
        >
        @error('telefono')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Correo electrónico</label>
        <input
            type="email"
            name="email"
            class="form-control @error('email') is-invalid @enderror"
            value="{{ old('email', $cliente->email ?? '') }}"
            placeholder="Ej: cliente@gmail.com"
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Dirección</label>
        <textarea
            name="direccion"
            rows="3"
            class="form-control @error('direccion') is-invalid @enderror"
            placeholder="Ej: Santa Cruz, Guanacaste, 200 metros al norte del parque"
        >{{ old('direccion', $cliente->direccion ?? '') }}</textarea>
        @error('direccion')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>