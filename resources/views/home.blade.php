@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="mb-0">Mis Tareas</h2>
                    <a href="{{ route('tareas.index') }}" class="btn btn-primary">Ver todas las tareas</a>
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
