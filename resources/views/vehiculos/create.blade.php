@extends('layouts.admin')

@section('title', 'Crear Vehículo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('vehiculos.index')}}">Vehículos</a></li>
<li class="breadcrumb-item active">Crear</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8 mx-auto">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-plus mr-1"></i>
          Crear Nuevo Vehículo
        </h3>
      </div>

      <form action="{{ route('vehiculos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
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

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="patente">Patente <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('patente') is-invalid @enderror" 
                       id="patente" 
                       name="patente" 
                       value="{{ old('patente') }}" 
                       placeholder="Ej: ABC123"
                       required>
                @error('patente')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="color">Color <span class="text-danger">*</span></label>
                <input type="text" 
                       class="form-control @error('color') is-invalid @enderror" 
                       id="color" 
                       name="color" 
                       value="{{ old('color') }}" 
                       placeholder="Ej: Rojo"
                       required>
                @error('color')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="anio">Año <span class="text-danger">*</span></label>
                <input type="number" 
                       class="form-control @error('anio') is-invalid @enderror" 
                       id="anio" 
                       name="anio" 
                       value="{{ old('anio', date('Y')) }}" 
                       min="1900" 
                       max="{{ date('Y') + 1 }}"
                       required>
                @error('anio')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="modelo_id">Modelo <span class="text-danger">*</span></label>
                <select class="form-control @error('modelo_id') is-invalid @enderror" 
                        id="modelo_id" 
                        name="modelo_id" 
                        required>
                  <option value="">Seleccione un modelo</option>
                  @foreach($modelos as $modelo)
                    <option value="{{ $modelo->id }}" {{ old('modelo_id') == $modelo->id ? 'selected' : '' }}>
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
            <div class="col-12">
              <div class="form-group">
                <label for="imagen">Imagen del Vehículo</label>
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" 
                           class="custom-file-input @error('imagen') is-invalid @enderror" 
                           id="imagen" 
                           name="imagen"
                           accept="image/*">
                    <label class="custom-file-label" for="imagen">Seleccionar imagen...</label>
                  </div>
                </div>
                @error('imagen')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">
                  Formatos permitidos: JPG, JPEG, PNG, GIF. Tamaño máximo: 2MB.
                </small>
              </div>
            </div>
          </div>

          @if($modelos->isEmpty())
            <div class="alert alert-warning">
              <h5><i class="icon fas fa-exclamation-triangle"></i> ¡Atención!</h5>
              No hay modelos disponibles. Necesitas crear al menos un modelo antes de poder registrar vehículos.
            </div>
          @endif
        </div>

        <div class="card-footer">
          <div class="row">
            <div class="col-md-6">
              <button type="submit" class="btn btn-primary" {{ $modelos->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-save"></i> Guardar Vehículo
              </button>
            </div>
            <div class="col-md-6 text-right">
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

  // Mostrar nombre del archivo seleccionado
  $('#imagen').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).siblings('.custom-file-label').addClass("selected").html(fileName);
    
    // Previsualización de imagen
    if (this.files && this.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        var preview = '<div class="mt-2"><img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px; max-height: 150px;"><p class="text-muted mt-1">Vista previa</p></div>';
        $('#imagen').closest('.form-group').find('.preview').remove();
        $('#imagen').closest('.form-group').append('<div class="preview">' + preview + '</div>');
      }
      reader.readAsDataURL(this.files[0]);
    }
  });
});
</script>
@endpush
