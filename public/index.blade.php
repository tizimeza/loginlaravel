@extends('layouts.admin')
@section('content')
<div class = "container">
    <div class = "row justify-content-center">
        <div class = "col-md-12">
            <div class = "card">
                <div class = "card-header d-flex justify-content-between align-items-left">
                    <span>{{__('Dashboard') }}</span>
                    <a href="{{ route('tareas.create') }}" class="btn btn-primary btn-sm">Crear Tarea</a>
                </div>
                <div class = "card-body">
                    @if (session('success'))
                        <div class = "alert alert-success" role = "alert">
                            {{session('success') }}
                        </div>
                    @endif
                    <table class = "table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tareas as $tarea)
                                <tr>
                                    <td>{{ $tarea->id }}</td>
                                    <td>{{ $tarea->nombre }}</td>
                                    <td>
                                        <a href="{{ route('tareas.edit', $tarea->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <form action="{{ route('tareas.destroy', $tarea->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Estas seguro de eliminar esta tarea?')">
                                                Eliminar
                                            </button>   
                                        </form>     
                                    </td>                                    
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> 
            </div>
        </div>
    </div>        
</div>
@endsection
