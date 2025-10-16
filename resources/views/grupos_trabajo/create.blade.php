@extends('layouts.admin')

@section('title', 'Crear Nuevo Equipo de Trabajo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('grupos_trabajo.index')}}">Equipos de Trabajo</a></li>
<li class="breadcrumb-item active">Crear Nuevo</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-plus mr-1"></i>
          Crear Nuevo Equipo de Trabajo
        </h3>
        <div class="card-tools">
          <a href="{{ route('grupos_trabajo.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <form action="{{ route('grupos_trabajo.store') }}" method="POST">
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
            <!-- Información Básica -->
            <div class="col-md-6">
              <div class="card card-outline card-primary">
                <div class="card-header">
                  <h4 class="card-title">
                    <i class="fas fa-info-circle mr-1"></i>
                    Información Básica
                  </h4>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="nombre">Nombre del Equipo *</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror"
                           id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                    @error('nombre')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea class="form-control @error('descripcion') is-invalid @enderror"
                              id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="lider_id">Líder del Equipo *</label>
                    <select class="form-control @error('lider_id') is-invalid @enderror" id="lider_id" name="lider_id" required>
                      <option value="">Seleccionar líder...</option>
                      @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ old('lider_id') == $usuario->id ? 'selected' : '' }}>
                          {{ $usuario->name }}
                        </option>
                      @endforeach
                    </select>
                    @error('lider_id')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <!-- Configuración -->
            <div class="col-md-6">
              <div class="card card-outline card-warning">
                <div class="card-header">
                  <h4 class="card-title">
                    <i class="fas fa-cogs mr-1"></i>
                    Configuración
                  </h4>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label for="especialidad">Especialidad *</label>
                    <select class="form-control @error('especialidad') is-invalid @enderror" id="especialidad" name="especialidad" required>
                      <option value="">Seleccionar especialidad...</option>
                      @foreach($especialidades as $key => $especialidad)
                        <option value="{{ $key }}" {{ old('especialidad') == $key ? 'selected' : '' }}>
                          {{ $especialidad }}
                        </option>
                      @endforeach
                    </select>
                    @error('especialidad')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="color">Color de Identificación</label>
                    <select class="form-control @error('color') is-invalid @enderror" id="color" name="color">
                      @foreach($colores as $key => $color)
                        <option value="{{ $key }}" {{ old('color', 'primary') == $key ? 'selected' : '' }}>
                          {{ $color }}
                        </option>
                      @endforeach
                    </select>
                    @error('color')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="capacidad_maxima">Capacidad Máxima</label>
                    <input type="number" class="form-control @error('capacidad_maxima') is-invalid @enderror"
                           id="capacidad_maxima" name="capacidad_maxima" min="1" max="10"
                           value="{{ old('capacidad_maxima', 3) }}">
                    @error('capacidad_maxima')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <label for="zona_trabajo">Zona de Trabajo</label>
                    <select class="form-control @error('zona_trabajo') is-invalid @enderror" id="zona_trabajo" name="zona_trabajo">
                      <option value="">Seleccionar zona...</option>
                      @foreach(\App\Models\GrupoTrabajo::ZONAS_TRABAJO as $key => $zona)
                        <option value="{{ $key }}" {{ old('zona_trabajo') == $key ? 'selected' : '' }}>
                          {{ $zona }}
                        </option>
                      @endforeach
                    </select>
                    @error('zona_trabajo')
                      <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                      </span>
                    @enderror
                  </div>

                  <div class="form-group">
                    <div class="custom-control custom-switch">
                      <input type="hidden" name="activo" value="0">
                      <input type="checkbox" class="custom-control-input" id="activo" name="activo" value="1"
                             {{ old('activo', true) ? 'checked' : '' }}>
                      <label class="custom-control-label" for="activo">Grupo Activo</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Miembros del Grupo -->
          <div class="row">
            <div class="col-12">
              <div class="card card-outline card-success">
                <div class="card-header">
                  <h4 class="card-title">
                    <i class="fas fa-users mr-1"></i>
                    Miembros del Grupo
                  </h4>
                </div>
                <div class="card-body">
                  <div class="form-group">
                    <label>Seleccionar Miembros</label>
                    <select class="form-control select2" id="miembros" name="miembros[]" multiple>
                      @foreach($usuarios as $usuario)
                        <option value="{{ $usuario->id }}" {{ in_array($usuario->id, old('miembros', [])) ? 'selected' : '' }}>
                          {{ $usuario->name }}
                        </option>
                      @endforeach
                    </select>
                    <small class="form-text text-muted">Selecciona los miembros que formarán parte del equipo de trabajo.</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Crear Equipo de Trabajo
          </button>
          <a href="{{ route('grupos_trabajo.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('css')
<!-- Select2 CSS -->
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('adminlte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('js')
<!-- Select2 JS -->
<script src="{{ asset('adminlte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        placeholder: 'Seleccionar miembros...',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endsection
