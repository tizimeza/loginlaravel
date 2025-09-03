@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $tareas->count() }}</h3>
        <p>Total Tareas</p>
      </div>
      <div class="icon">
        <i class="fas fa-tasks"></i>
      </div>
      <a href="{{ route('tareas.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $tareas->where('completada', true)->count() }}</h3>
        <p>Completadas</p>
      </div>
      <div class="icon">
        <i class="fas fa-check"></i>
      </div>
      <a href="{{ route('tareas.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $tareas->where('completada', false)->count() }}</h3>
        <p>Pendientes</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
      <a href="{{ route('tareas.index') }}" class="small-box-footer">Más info <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
  <div class="col-lg-3 col-6">
    <!-- small box -->
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ Auth::user()->name }}</h3>
        <p>Usuario Activo</p>
      </div>
      <div class="icon">
        <i class="fas fa-user"></i>
      </div>
      <a href="#" class="small-box-footer">Perfil <i class="fas fa-arrow-circle-right"></i></a>
    </div>
  </div>
  <!-- ./col -->
</div>
<!-- /.row -->

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-tasks mr-1"></i>
          Gestión de Tareas
        </h3>
        <div class="card-tools">
          <a href="{{ route('tareas.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> Ver todas las tareas
          </a>
        </div>
      </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="{{ route('tareas.store') }}" method="POST" class="d-flex gap-2">
                                @csrf
                                <div class="flex-grow-1">
                                    <input type="text" 
                                           name="nombre" 
                                           class="form-control @error('nombre') is-invalid @enderror" 
                                           placeholder="Nueva tarea" 
                                           value="{{ old('nombre') }}" 
                                           required>
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Agregar</button>
                            </form>
                        </div>
                    </div>

                    @if($tareas->isEmpty())
                        <p class="text-center text-muted">No hay tareas pendientes</p>
                    @else
                        <ul class="list-group">
                            @foreach($tareas as $tarea)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="completada" value="{{ $tarea->completada ? 0 : 1 }}">
                                            <button type="submit" class="btn btn-sm {{ $tarea->completada ? 'btn-success' : 'btn-outline-success' }}">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        </form>
                                        <span class="{{ $tarea->completada ? 'text-decoration-line-through text-muted' : '' }}">
                                            {{ $tarea->nombre }}
                                        </span>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta tarea?')">Eliminar</button>
                                        </form>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
