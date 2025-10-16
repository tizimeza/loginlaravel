@extends('layouts.admin')

@section('title', 'Editar Vehículo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('vehiculos.index')}}">Vehículos</a></li>
<li class="breadcrumb-item"><a href="{{route('vehiculos.show', $vehiculo)}}">{{ $vehiculo->patente }}</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-edit mr-1"></i>
          Editar Vehículo: {{ $vehiculo->patente }}
        </h3>
      </div>

      <form action="{{ route('vehiculos.update', $vehiculo) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <h5><i class="icon fas fa-ban"></i> ¡Error!</h5>
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <!-- Información Básica -->
          <h5 class="mb-3"><i class="fas fa-info-circle"></i> Información Básica</h5>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="patente">Patente <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control @error('patente') is-invalid @enderror"
                       id="patente"
                       name="patente"
                       value="{{ old('patente', $vehiculo->patente) }}"
                       placeholder="Ej: ABC 123"
                       required>
                @error('patente')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="tipo_vehiculo">Tipo de Vehículo <span class="text-danger">*</span></label>
                <select class="form-control @error('tipo_vehiculo') is-invalid @enderror"
                        id="tipo_vehiculo"
                        name="tipo_vehiculo"
                        required>
                  <option value="">Seleccione...</option>
                  <option value="transit" {{ old('tipo_vehiculo', $vehiculo->tipo_vehiculo) == 'transit' ? 'selected' : '' }}>Ford Transit</option>
                  <option value="kangoo" {{ old('tipo_vehiculo', $vehiculo->tipo_vehiculo) == 'kangoo' ? 'selected' : '' }}>Renault Kangoo</option>
                  <option value="partner" {{ old('tipo_vehiculo', $vehiculo->tipo_vehiculo) == 'partner' ? 'selected' : '' }}>Peugeot Partner</option>
                </select>
                @error('tipo_vehiculo')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="marca">Marca <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control @error('marca') is-invalid @enderror"
                       id="marca"
                       name="marca"
                       value="{{ old('marca', $vehiculo->marca) }}"
                       placeholder="Ej: Ford"
                       required>
                @error('marca')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="modelo_id">Modelo <span class="text-danger">*</span></label>
                <select class="form-control @error('modelo_id') is-invalid @enderror"
                        id="modelo_id"
                        name="modelo_id"
                        required>
                  <option value="">Seleccione un modelo</option>
                  @foreach($modelos as $modelo)
                    <option value="{{ $modelo->id }}" {{ old('modelo_id', $vehiculo->modelo_id) == $modelo->id ? 'selected' : '' }}>
                      {{ $modelo->marca->nombre ?? 'Sin marca' }} - {{ $modelo->nombre }}
                    </option>
                  @endforeach
                </select>
                @error('modelo_id')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="color">Color <span class="text-danger">*</span></label>
                <input type="text"
                       class="form-control @error('color') is-invalid @enderror"
                       id="color"
                       name="color"
                       value="{{ old('color', $vehiculo->color) }}"
                       placeholder="Ej: Blanco"
                       required>
                @error('color')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="anio">Año <span class="text-danger">*</span></label>
                <input type="number"
                       class="form-control @error('anio') is-invalid @enderror"
                       id="anio"
                       name="anio"
                       value="{{ old('anio', $vehiculo->anio) }}"
                       min="1900"
                       max="{{ date('Y') + 1 }}"
                       required>
                @error('anio')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="capacidad_carga">Capacidad de Carga (kg) <span class="text-danger">*</span></label>
                <input type="number"
                       class="form-control @error('capacidad_carga') is-invalid @enderror"
                       id="capacidad_carga"
                       name="capacidad_carga"
                       value="{{ old('capacidad_carga', $vehiculo->capacidad_carga) }}"
                       placeholder="Ej: 1500"
                       min="1"
                       required>
                @error('capacidad_carga')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="combustible">Tipo de Combustible <span class="text-danger">*</span></label>
                <select class="form-control @error('combustible') is-invalid @enderror"
                        id="combustible"
                        name="combustible"
                        required>
                  <option value="">Seleccione...</option>
                  <option value="nafta" {{ old('combustible', $vehiculo->combustible) == 'nafta' ? 'selected' : '' }}>Nafta</option>
                  <option value="diesel" {{ old('combustible', $vehiculo->combustible) == 'diesel' ? 'selected' : '' }}>Diesel</option>
                  <option value="gnc" {{ old('combustible', $vehiculo->combustible) == 'gnc' ? 'selected' : '' }}>GNC</option>
                  <option value="electrico" {{ old('combustible', $vehiculo->combustible) == 'electrico' ? 'selected' : '' }}>Eléctrico</option>
                </select>
                @error('combustible')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Estado y Mantenimiento -->
          <h5 class="mb-3 mt-4"><i class="fas fa-wrench"></i> Estado y Mantenimiento</h5>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label for="estado">Estado del Vehículo <span class="text-danger">*</span></label>
                <select class="form-control @error('estado') is-invalid @enderror"
                        id="estado"
                        name="estado"
                        required>
                  <option value="">Seleccione...</option>
                  <option value="disponible" {{ old('estado', $vehiculo->estado) == 'disponible' ? 'selected' : '' }}>Disponible</option>
                  <option value="en_uso" {{ old('estado', $vehiculo->estado) == 'en_uso' ? 'selected' : '' }}>En Uso</option>
                  <option value="mantenimiento" {{ old('estado', $vehiculo->estado) == 'mantenimiento' ? 'selected' : '' }}>En Mantenimiento</option>
                  <option value="fuera_servicio" {{ old('estado', $vehiculo->estado) == 'fuera_servicio' ? 'selected' : '' }}>Fuera de Servicio</option>
                </select>
                @error('estado')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="kilometraje">Kilometraje Actual (km) <span class="text-danger">*</span></label>
                <input type="number"
                       class="form-control @error('kilometraje') is-invalid @enderror"
                       id="kilometraje"
                       name="kilometraje"
                       value="{{ old('kilometraje', $vehiculo->kilometraje) }}"
                       placeholder="Ej: 45000"
                       min="0"
                       required>
                @error('kilometraje')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="fecha_vencimiento_vtv">Vencimiento VTV</label>
                <input type="date"
                       class="form-control @error('fecha_vencimiento_vtv') is-invalid @enderror"
                       id="fecha_vencimiento_vtv"
                       name="fecha_vencimiento_vtv"
                       value="{{ old('fecha_vencimiento_vtv', $vehiculo->fecha_vencimiento_vtv ? $vehiculo->fecha_vencimiento_vtv->format('Y-m-d') : '') }}">
                @error('fecha_vencimiento_vtv')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-3">
              <div class="form-group">
                <label for="fecha_cambio_neumaticos">Próximo Cambio de Neumáticos</label>
                <input type="date"
                       class="form-control @error('fecha_cambio_neumaticos') is-invalid @enderror"
                       id="fecha_cambio_neumaticos"
                       name="fecha_cambio_neumaticos"
                       value="{{ old('fecha_cambio_neumaticos', $vehiculo->fecha_cambio_neumaticos ? $vehiculo->fecha_cambio_neumaticos->format('Y-m-d') : '') }}">
                @error('fecha_cambio_neumaticos')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="servicios_pendientes">Servicios Pendientes</label>
                <textarea class="form-control @error('servicios_pendientes') is-invalid @enderror"
                          id="servicios_pendientes"
                          name="servicios_pendientes"
                          rows="3"
                          placeholder="Ej: Cambio de aceite programado para la próxima semana">{{ old('servicios_pendientes', $vehiculo->servicios_pendientes) }}</textarea>
                @error('servicios_pendientes')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="observaciones">Observaciones</label>
                <textarea class="form-control @error('observaciones') is-invalid @enderror"
                          id="observaciones"
                          name="observaciones"
                          rows="3"
                          placeholder="Ej: Furgoneta principal para instalaciones grandes. Equipada con herramientas completas.">{{ old('observaciones', $vehiculo->observaciones) }}</textarea>
                @error('observaciones')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <!-- Imagen -->
          <h5 class="mb-3 mt-4"><i class="fas fa-image"></i> Imagen del Vehículo</h5>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="imagen">Imagen del Vehículo</label>
                @if($vehiculo->imagen)
                  <div class="mb-2">
                    <img src="{{ asset('storage/' . $vehiculo->imagen) }}"
                         alt="Imagen actual del vehículo"
                         class="img-thumbnail"
                         style="max-width: 100%; max-height: 200px;">
                    <p class="text-muted mt-2">Imagen actual</p>
                  </div>
                @endif
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file"
                           class="custom-file-input @error('imagen') is-invalid @enderror"
                           id="imagen"
                           name="imagen"
                           accept="image/*">
                    <label class="custom-file-label" for="imagen">
                      {{ $vehiculo->imagen ? 'Cambiar imagen...' : 'Seleccionar imagen...' }}
                    </label>
                  </div>
                </div>
                @error('imagen')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                  Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB.
                  {{ $vehiculo->imagen ? 'Deja vacío para mantener la imagen actual.' : '' }}
                </small>
              </div>
            </div>
            <div class="col-md-6">
              <div id="imagen-preview"></div>
            </div>
          </div>

          <!-- Información adicional -->
          <div class="row mt-4">
            <div class="col-12">
              <div class="card card-outline card-info">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-info-circle"></i>
                    Información del Registro
                  </h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-6">
                      <strong>Fecha de Creación:</strong><br>
                      <span class="text-muted">{{ $vehiculo->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div class="col-md-6">
                      <strong>Última Actualización:</strong><br>
                      <span class="text-muted">{{ $vehiculo->updated_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-md-4">
              <button type="submit" class="btn btn-warning">
                <i class="fas fa-save"></i> Actualizar Vehículo
              </button>
            </div>
            <div class="col-md-8 text-right">
              <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-info mr-2">
                <i class="fas fa-eye"></i> Ver Detalles
              </a>
              <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
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
  // Convertir patente a mayúsculas automáticamente
  $('#patente').on('input', function() {
    this.value = this.value.toUpperCase();
  });

  // Capitalizar primera letra del color
  $('#color').on('input', function() {
    let value = this.value.toLowerCase();
    this.value = value.charAt(0).toUpperCase() + value.slice(1);
  });

  // Capitalizar primera letra de la marca
  $('#marca').on('input', function() {
    let value = this.value.toLowerCase();
    this.value = value.charAt(0).toUpperCase() + value.slice(1);
  });

  // Mostrar nombre del archivo seleccionado y previsualización
  $('#imagen').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).siblings('.custom-file-label').addClass("selected").html(fileName);

    // Previsualización de imagen
    if (this.files && this.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('#imagen-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 100%; max-height: 200px;"><p class="text-muted mt-2 text-center">Nueva imagen seleccionada</p>');
      }
      reader.readAsDataURL(this.files[0]);
    } else {
      $('#imagen-preview').html('');
    }
  });

  // Autocompletar marca según el modelo seleccionado
  $('#modelo_id').on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var modeloText = selectedOption.text();
    if (modeloText && modeloText !== 'Seleccione un modelo') {
      var marca = modeloText.split(' - ')[0];
      if (marca && marca !== 'Sin marca') {
        $('#marca').val(marca);
      }
    }
  });
});
</script>
@endpush
