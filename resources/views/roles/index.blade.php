@extends('layouts.admin')

@section('title', 'Gestión de Roles')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Roles</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-tag mr-1"></i> Lista de Roles</h3>
        <div class="card-tools">
          <a href="{{ route('roles.create') }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Nuevo Rol
          </a>
        </div>
      </div>

      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-check"></i> {{ session('success') }}
          </div>
        @endif

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-ban"></i> {{ session('error') }}
          </div>
        @endif

        <div class="table-responsive">
          <table class="table table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre del Rol</th>
                <th>Permisos</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse($roles as $role)
                <tr>
                  <td>{{ $role->id }}</td>
                  <td><strong>{{ $role->name }}</strong></td>
                  <td>
                    <span class="badge badge-info">{{ $role->permissions->count() }} permisos</span>
                  </td>
                  <td>
                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-sm">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('roles.destroy', $role) }}" method="POST" class="d-inline">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-sm" 
                              onclick="return confirm('¿Eliminar este rol?')">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="4" class="text-center">No hay roles registrados</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3">
          {{ $roles->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
