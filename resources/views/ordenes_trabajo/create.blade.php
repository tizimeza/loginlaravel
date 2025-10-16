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
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-plus mr-1"></i>
          Crear Nueva Orden de Trabajo
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
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Errores en el formulario</h5>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <!-- CLIENTE -->
            <div class="col-md-6">
              <div class="card card-outline card-primary">
                <div class="card-header">
                  <h3 class="card-title">Cliente</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="cliente_id">Seleccionar Cliente <span class="text-danger">*</span></label>
                    <select class="form-control @error('cliente_id') is-invalid @enderror"
                            id="cliente_id" name="cliente_id" required>
                      <option value="">-- Seleccionar --</option>
                      @foreach($clientes as $cliente)
                        <option value="{{ $cliente->id }}"
                                data-telefono="{{ $cliente->telefono }}"
                                data-email="{{ $cliente->email }}"
                                {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                          {{ $cliente->nombre }} - {{ $cliente->tipo_cliente_formateado }}
                        </option>
                      @endforeach
                    </select>
                    @error('cliente_id')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="cliente_telefono">Teléfono</label>
                    <input type="text" class="form-control" id="cliente_telefono"
                           name="cliente_telefono" value="{{ old('cliente_telefono') }}" readonly>
                  </div>

                  <div class="form-group">
                    <label for="cliente_email">Email</label>
                    <input type="email" class="form-control" id="cliente_email"
                           name="cliente_email" value="{{ old('cliente_email') }}" readonly>
                  </div>
                </div>
              </div>
            </div>

            <!-- VEHÍCULO Y ASIGNACIÓN -->
            <div class="col-md-6">
              <div class="card card-outline card-success">
                <div class="card-header">
                  <h3 class="card-title">Vehículo y Asignación</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="vehiculo_id">Vehículo <span class="text-danger">*</span></label>
                    <select class="form-control @error('vehiculo_id') is-invalid @enderror"
                            id="vehiculo_id" name="vehiculo_id" required>
                      <option value="">-- Seleccionar --</option>
                      @foreach($vehiculos as $vehiculo)
                        <option value="{{ $vehiculo->id }}" {{ old('vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                          {{ $vehiculo->patente }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                        </option>
                      @endforeach
                    </select>
                    @error('vehiculo_id')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
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

          <div class="row">
            <!-- DESCRIPCIÓN -->
            <div class="col-md-8">
              <div class="card card-outline card-warning">
                <div class="card-header">
                  <h3 class="card-title">Descripción del Problema</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="descripcion_problema">Descripción <span class="text-danger">*</span></label>
                    <textarea class="form-control @error('descripcion_problema') is-invalid @enderror"
                              id="descripcion_problema" name="descripcion_problema" rows="4" required>{{ old('descripcion_problema') }}</textarea>
                    @error('descripcion_problema')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="observaciones">Observaciones</label>
                    <textarea class="form-control @error('observaciones') is-invalid @enderror"
                              id="observaciones" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- FECHAS Y COSTOS -->
            <div class="col-md-4">
              <div class="card card-outline card-info">
                <div class="card-header">
                  <h3 class="card-title">Fechas y Costos</h3>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="fecha_ingreso">Fecha Ingreso <span class="text-danger">*</span></label>
                    <input type="datetime-local" class="form-control @error('fecha_ingreso') is-invalid @enderror"
                           id="fecha_ingreso" name="fecha_ingreso"
                           value="{{ old('fecha_ingreso', now()->format('Y-m-d\TH:i')) }}" required>
                    @error('fecha_ingreso')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="fecha_estimada_entrega">Fecha Estimada</label>
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
                      <input type="number" step="0.01" min="0" class="form-control @error('costo_estimado') is-invalid @enderror"
                             id="costo_estimado" name="costo_estimado" value="{{ old('costo_estimado') }}">
                    </div>
                    @error('costo_estimado')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="estado">Estado</label>
                    <select class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado">
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
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-success btn-lg">
            <i class="fas fa-save"></i> CREAR ORDEN DE TRABAJO
          </button>
          <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary btn-lg">
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
  // Auto-completar datos del cliente
  $('#cliente_id').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var telefono = selectedOption.data('telefono') || '';
    var email = selectedOption.data('email') || '';

    $('#cliente_telefono').val(telefono);
    $('#cliente_email').val(email);
  });

  // Auto-calcular fecha estimada (7 días después)
  $('#fecha_ingreso').on('change', function() {
    if (this.value && !$('#fecha_estimada_entrega').val()) {
      var fechaIngreso = new Date(this.value);
      fechaIngreso.setDate(fechaIngreso.getDate() + 7);

      var year = fechaIngreso.getFullYear();
      var month = String(fechaIngreso.getMonth() + 1).padStart(2, '0');
      var day = String(fechaIngreso.getDate()).padStart(2, '0');
      var hours = String(fechaIngreso.getHours()).padStart(2, '0');
      var minutes = String(fechaIngreso.getMinutes()).padStart(2, '0');

      $('#fecha_estimada_entrega').val(year + '-' + month + '-' + day + 'T' + hours + ':' + minutes);
    }
  });

  // Trigger change si hay cliente seleccionado
  if ($('#cliente_id').val()) {
    $('#cliente_id').trigger('change');
  }
});
</script>
@endpush
