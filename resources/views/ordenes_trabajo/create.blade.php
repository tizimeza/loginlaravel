@extends('layouts.admin')

@section('title', 'Nueva Orden de Trabajo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('ordenes_trabajo.index')}}">Órdenes de Trabajo</a></li>
<li class="breadcrumb-item active">Nueva Orden</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-plus mr-1"></i>
          Nueva Orden de Trabajo
        </h3>
        <div class="card-tools">
          <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <form action="{{ route('ordenes_trabajo.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <h5><i class="icon fas fa-ban"></i> Error!</h5>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <!-- Información del Cliente -->
            <div class="col-md-6">
              <div class="card card-outline card-info">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-user"></i> Información del Cliente</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="cliente_nombre">Nombre del Cliente <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('cliente_nombre') is-invalid @enderror" 
                           id="cliente_nombre" name="cliente_nombre" value="{{ old('cliente_nombre') }}" required>
                    @error('cliente_nombre')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="cliente_telefono">Teléfono</label>
                    <input type="text" class="form-control @error('cliente_telefono') is-invalid @enderror" 
                           id="cliente_telefono" name="cliente_telefono" value="{{ old('cliente_telefono') }}">
                    @error('cliente_telefono')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="cliente_email">Email</label>
                    <input type="email" class="form-control @error('cliente_email') is-invalid @enderror" 
                           id="cliente_email" name="cliente_email" value="{{ old('cliente_email') }}">
                    @error('cliente_email')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- Información del Vehículo y Orden -->
            <div class="col-md-6">
              <div class="card card-outline card-success">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-car"></i> Información del Vehículo</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="vehiculo_id">Vehículo <span class="text-danger">*</span></label>
                    <select class="form-control @error('vehiculo_id') is-invalid @enderror" 
                            id="vehiculo_id" name="vehiculo_id" required>
                      <option value="">Seleccionar vehículo</option>
                      @foreach($vehiculos as $vehiculo)
                        <option value="{{ $vehiculo->id }}" {{ old('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                          {{ $vehiculo->patente }} - 
                          @if($vehiculo->modelo && $vehiculo->modelo->marca)
                            {{ $vehiculo->modelo->marca->nombre }} {{ $vehiculo->modelo->nombre }}
                          @else
                            Sin modelo
                          @endif
                          ({{ $vehiculo->color }}, {{ $vehiculo->anio }})
                        </option>
                      @endforeach
                    </select>
                    @error('vehiculo_id')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="numero_orden">Número de Orden</label>
                    <input type="text" class="form-control @error('numero_orden') is-invalid @enderror" 
                           id="numero_orden" name="numero_orden" value="{{ old('numero_orden') }}" 
                           placeholder="Se generará automáticamente si se deja vacío">
                    @error('numero_orden')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Dejar vacío para generar automáticamente</small>
                  </div>

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="estado">Estado <span class="text-danger">*</span></label>
                        <select class="form-control @error('estado') is-invalid @enderror" 
                                id="estado" name="estado" required>
                          @foreach($estados as $key => $estado)
                            <option value="{{ $key }}" {{ old('estado', 'pendiente') == $key ? 'selected' : '' }}>
                              {{ $estado }}
                            </option>
                          @endforeach
                        </select>
                        @error('estado')
                          <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="prioridad">Prioridad <span class="text-danger">*</span></label>
                        <select class="form-control @error('prioridad') is-invalid @enderror" 
                                id="prioridad" name="prioridad" required>
                          @foreach($prioridades as $key => $prioridad)
                            <option value="{{ $key }}" {{ old('prioridad', 'media') == $key ? 'selected' : '' }}>
                              {{ $prioridad }}
                            </option>
                          @endforeach
                        </select>
                        @error('prioridad')
                          <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Descripción del Problema -->
            <div class="col-md-8">
              <div class="card card-outline card-warning">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Descripción del Problema</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="descripcion_problema">Descripción del Problema <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('descripcion_problema') is-invalid @enderror" 
                              id="descripcion_problema" name="descripcion_problema" rows="4" required>{{ old('descripcion_problema') }}</textarea>
                    @error('descripcion_problema')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="observaciones">Observaciones Adicionales</label>
                    <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                              id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- Fechas y Costos -->
            <div class="col-md-4">
              <div class="card card-outline card-primary">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-calendar-alt"></i> Fechas y Costos</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="fecha_ingreso">Fecha de Ingreso <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('fecha_ingreso') is-invalid @enderror" 
                           id="fecha_ingreso" name="fecha_ingreso" 
                           value="{{ old('fecha_ingreso', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha_ingreso')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="fecha_estimada_entrega">Fecha Estimada de Entrega</label>
                    <input type="datetime-local" class="form-control @error('fecha_estimada_entrega') is-invalid @enderror" 
                           id="fecha_estimada_entrega" name="fecha_estimada_entrega" 
                           value="{{ old('fecha_estimada_entrega') }}">
                    @error('fecha_estimada_entrega')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="costo_estimado">Costo Estimado</label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input type="number" step="0.01" min="0" 
                             class="form-control @error('costo_estimado') is-invalid @enderror" 
                             id="costo_estimado" name="costo_estimado" 
                             value="{{ old('costo_estimado') }}">
                      @error('costo_estimado')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="user_id">Técnico Asignado</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" 
                            id="user_id" name="user_id">
                      <option value="">Sin asignar</option>
                      @foreach($tecnicos as $tecnico)
                        <option value="{{ $tecnico->id }}" {{ old('user_id') == $tecnico->id ? 'selected' : '' }}>
                          {{ $tecnico->name }}
                        </option>
                      @endforeach
                    </select>
                    @error('user_id')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Crear Orden de Trabajo
          </button>
          <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Auto-completar fecha estimada cuando se selecciona fecha de ingreso
  $('#fecha_ingreso').on('change', function() {
    if (this.value && !$('#fecha_estimada_entrega').val()) {
      let fechaIngreso = new Date(this.value);
      fechaIngreso.setDate(fechaIngreso.getDate() + 7); // Agregar 7 días por defecto
      
      let year = fechaIngreso.getFullYear();
      let month = String(fechaIngreso.getMonth() + 1).padStart(2, '0');
      let day = String(fechaIngreso.getDate()).padStart(2, '0');
      let hours = String(fechaIngreso.getHours()).padStart(2, '0');
      let minutes = String(fechaIngreso.getMinutes()).padStart(2, '0');
      
      $('#fecha_estimada_entrega').val(`${year}-${month}-${day}T${hours}:${minutes}`);
    }
  });

  // Select2 para mejor UX en selects
  $('#vehiculo_id, #user_id').select2({
    theme: 'bootstrap4',
    width: '100%'
  });
});
</script>
@endpush
