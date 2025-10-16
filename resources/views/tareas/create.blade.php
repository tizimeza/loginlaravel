@extends('layouts.admin')

@section('title', 'Crear Nueva Tarea Plantilla')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('tareas.index')}}">Tareas</a></li>
<li class="breadcrumb-item active">Crear Nueva</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-plus mr-1"></i>
          Crear Nueva Tarea Plantilla
        </h3>
        <div class="card-tools">
          <a href="{{ route('tareas.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <form action="{{ route('tareas.store') }}" method="POST">
        @csrf
        <div class="card-body">
          @if($errors->any())
            <div class="alert alert-danger alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5><i class="icon fas fa-ban"></i> Error!</h5>
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="form-group">
            <label for="nombre">Nombre de la Tarea *</label>
            <input type="text"
                   class="form-control @error('nombre') is-invalid @enderror"
                   id="nombre"
                   name="nombre"
                   placeholder="Ejemplo: Verificar instalación del router"
                   value="{{ old('nombre') }}"
                   required>
            <small class="form-text text-muted">
              Ingresa un nombre descriptivo para la tarea plantilla.
            </small>
            @error('nombre')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="tipo">Tipo de Tarea *</label>
                <select class="form-control @error('tipo') is-invalid @enderror"
                        id="tipo"
                        name="tipo"
                        required>
                  <option value="">Seleccionar tipo...</option>
                  @foreach($tipos as $key => $tipo)
                    <option value="{{ $key }}" {{ old('tipo') == $key ? 'selected' : '' }}>
                      {{ $tipo }}
                    </option>
                  @endforeach
                </select>
                <small class="form-text text-muted">
                  Selecciona el tipo de tarea que mejor describa esta actividad.
                </small>
                @error('tipo')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="estado">Estado Inicial *</label>
                <select class="form-control @error('estado') is-invalid @enderror"
                        id="estado"
                        name="estado"
                        required>
                  <option value="">Seleccionar estado...</option>
                  @foreach(\App\Models\Tarea::ESTADOS as $key => $estado)
                    <option value="{{ $key }}" {{ old('estado', 'pendiente') == $key ? 'selected' : '' }}>
                      {{ $estado }}
                    </option>
                  @endforeach
                </select>
                <small class="form-text text-muted">
                  Estado con el que se creará la tarea cuando se asigne a una orden.
                </small>
                @error('estado')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
          </div>

          <div class="callout callout-info">
            <h5><i class="fas fa-info-circle"></i> Nota Importante</h5>
            <p class="mb-0">
              Esta es una tarea <strong>plantilla</strong> que podrás asignar a órdenes de trabajo más adelante.
              Las tareas plantilla sirven como modelos reutilizables para estandarizar el trabajo.
            </p>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Crear Tarea Plantilla
          </button>
          <a href="{{ route('tareas.index') }}" class="btn btn-default">
            <i class="fas fa-times"></i> Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
