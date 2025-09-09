@extends('layouts.admin')

@section('title', 'Gestión de Tareas')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Tareas</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-tasks mr-1"></i>
          Lista de Tareas
        </h3>
        <div class="card-tools">
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-nueva-tarea">
            <i class="fas fa-plus"></i> Nueva Tarea
          </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
            {{ session('success') }}
          </div>
        @endif

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

        @if($tareas->isEmpty())
          <div class="callout callout-info">
            <h5>No hay tareas</h5>
            <p>No tienes tareas registradas. ¡Crea tu primera tarea!</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tablaTareas">
              <thead>
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Tarea</th>
                  <th>Estado</th>
                  <th>Fecha Creación</th>
                  <th style="width: 150px">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($tareas as $tarea)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td class="{{ $tarea->completada ? 'text-decoration-line-through text-muted' : '' }}">
                      {{ $tarea->nombre }}
                    </td>
                    <td>
                      @if($tarea->completada)
                        <span class="badge badge-success">Completada</span>
                      @else
                        <span class="badge badge-warning">Pendiente</span>
                      @endif
                    </td>
                    <td>{{ $tarea->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                      <div class="btn-group btn-group-sm" role="group">
                        <form action="{{ route('tareas.update', $tarea->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PUT')
                          <input type="hidden" name="completada" value="{{ $tarea->completada ? 0 : 1 }}">
                          <button type="submit" class="btn {{ $tarea->completada ? 'btn-warning' : 'btn-success' }}" 
                                  title="{{ $tarea->completada ? 'Marcar como pendiente' : 'Marcar como completada' }}">
                            <i class="fas {{ $tarea->completada ? 'fa-undo' : 'fa-check' }}"></i>
                          </button>
                        </form>
                        <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-info" title="Editar">
                          <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" class="d-inline">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-danger" 
                                  onclick="return confirm('¿Estás seguro de eliminar esta tarea?')"
                                  title="Eliminar">
                            <i class="fas fa-trash"></i>
                          </button>
                        </form>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
</div>

<!-- Modal Nueva Tarea -->
<div class="modal fade" id="modal-nueva-tarea">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">N
