@extends('layouts.admin')

@section('title', 'Editar Cliente')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('clientes.index')}}">Clientes</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-edit mr-1"></i>
          Editar Cliente: {{ $cliente->nombre }}
        </h3>
      </div>

      <form action="{{ route('clientes.update', $cliente) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="nombre">Nombre del Cliente <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                       id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}"
                       placeholder="Ej: Juan Pérez" required>
                @error('nombre')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-4">
              <div class="form-group">
                <label for="tipo_cliente">Tipo de Cliente <span class="text-danger">*</span></label>
                <select class="form-control @error('tipo_cliente') is-invalid @enderror"
                        id="tipo_cliente" name="tipo_cliente" required>
                  <option value="">Seleccionar tipo</option>
                  @foreach($tiposCliente as $key => $tipo)
                    <option value="{{ $key }}"
                            {{ old('tipo_cliente', $cliente->tipo_cliente) == $key ? 'selected' : '' }}>
                      {{ $tipo }}
                    </option>
                  @endforeach
                </select>
                @error('tipo_cliente')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="telefono">Teléfono <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                       id="telefono" name="telefono" value="{{ old('telefono', $cliente->telefono) }}"
                       placeholder="Ej: +54 11 1234-5678" required>
                @error('telefono')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', $cliente->email) }}"
                       placeholder="Ej: cliente@email.com">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="direccion">Dirección <span class="text-danger">*</span></label>
            <textarea class="form-control @error('direccion') is-invalid @enderror"
                      id="direccion" name="direccion" rows="3" required>{{ old('direccion', $cliente->direccion) }}</textarea>
            @error('direccion')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="documento">Documento</label>
                <input type="text" class="form-control @error('documento') is-invalid @enderror"
                       id="documento" name="documento" value="{{ old('documento', $cliente->documento) }}"
                       placeholder="Ej: DNI 12345678">
                @error('documento')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="es_premium">Cliente Premium</label>
                <div class="custom-control custom-switch">
                  <input type="hidden" name="es_premium" value="0">
                  <input type="checkbox" class="custom-control-input @error('es_premium') is-invalid @enderror"
                         id="es_premium" name="es_premium" value="1"
                         {{ old('es_premium', $cliente->es_premium) ? 'checked' : '' }}>
                  <label class="custom-control-label" for="es_premium">
                    Marcar como cliente premium
                  </label>
                </div>
                @error('es_premium')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="activo">Estado</label>
                <div class="custom-control custom-switch">
                  <input type="hidden" name="activo" value="0">
                  <input type="checkbox" class="custom-control-input @error('activo') is-invalid @enderror"
                         id="activo" name="activo" value="1"
                         {{ old('activo', $cliente->activo) ? 'checked' : '' }}>
                  <label class="custom-control-label" for="activo">
                    Cliente activo
                  </label>
                </div>
                @error('activo')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="observaciones">Observaciones</label>
            <textarea class="form-control @error('observaciones') is-invalid @enderror"
                      id="observaciones" name="observaciones" rows="3">{{ old('observaciones', $cliente->observaciones) }}</textarea>
            @error('observaciones')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-md-6">
              <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar Cliente
              </button>
            </div>
            <div class="col-md-6 text-right">
              <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Ver Detalles
              </a>
              <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver al Listado
              </a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Formatear teléfono automáticamente
  $('#telefono').on('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length >= 10) {
      this.value = value.replace(/(\d{2})(\d{4})(\d{4})/, '$1 $2-$3');
    }
  });

  // Convertir nombre a title case
  $('#nombre').on('blur', function() {
    this.value = this.value.toLowerCase().replace(/\b\w/g, l => l.toUpperCase());
  });
});
</script>
@endpush
