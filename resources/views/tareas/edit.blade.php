@extends('layouts.admin')

@section('title', 'Editar Tarea')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('tareas.index')}}">Tareas</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-edit mr-1"></i>
          Editar Tarea
        </h3>
      </div>
      <!-- /.card-header -->
      <!-- form start -->
      <form action="{{ route('tareas.update', $tarea->id) }}" method="POST">
        @csrf
        @method('PUT')
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
                   placeholder="Ingresa el nombre de la tarea"
                   value="{{ old('nombre', $tarea->nombre) }}"
                   required>
            @error('nombre')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label for="tipo">Tipo de Tarea *</label>
            <select class="form-control @error('tipo') is-invalid @enderror"
                    id="tipo"
                    name="tipo"
                    required>
              <option value="">Seleccionar tipo...</option>
              @foreach($tipos as $key => $tipo)
                <option value="{{ $key }}" {{ old('tipo', $tarea->tipo) == $key ? 'selected' : '' }}>
                  {{ $tipo }}
                </option>
              @endforeach
            </select>
            @error('tipo')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>

          <div class="form-group">
            <label for="estado">Estado *</label>
            <select class="form-control @error('estado') is-invalid @enderror"
                    id="estado"
                    name="estado"
                    required>
              <option value="">Seleccionar estado...</option>
              @foreach($estados as $key => $estado)
                <option value="{{ $key }}" {{ old('estado', $tarea->estado) == $key ? 'selected' : '' }}>
                  {{ $estado }}
                </option>
              @endforeach
            </select>
            @error('estado')
              <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
              </span>
            @enderror
          </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Actualizar Tarea
          </button>
          <a href="{{ route('tareas.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </form>
    </div>
    <!-- /.card -->
  </div>

  <div class="col-md-6">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-info-circle mr-1"></i>
          Información de la Tarea
        </h3>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-sm-4">ID:</dt>
          <dd class="col-sm-8">{{ $tarea->id }}</dd>
          
          <dt class="col-sm-4">Estado actual:</dt>
          <dd class="col-sm-8">
            @if($tarea->completada)
              <span class="badge badge-success">Completada</span>
            @else
              <span class="badge badge-warning">Pendiente</span>
            @endif
          </dd>
          
          <dt class="col-sm-4">Fecha de creación:</dt>
          <dd class="col-sm-8">{{ $tarea->created_at->format('d/m/Y H:i:s') }}</dd>
          
          <dt class="col-sm-4">Última modificación:</dt>
          <dd class="col-sm-8">{{ $tarea->updated_at->format('d/m/Y H:i:s') }}</dd>
        </dl>
      </div>
    </div>
  </div>
</div>
@endsection