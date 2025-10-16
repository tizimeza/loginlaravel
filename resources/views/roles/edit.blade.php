@extends('layouts.admin')

@section('title', 'Editar Rol')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('roles.index')}}">Roles</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-10 offset-md-1">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-shield mr-1"></i> Editar Rol: {{ $role->name }}</h3>
      </div>

      <form action="{{ route('roles.update', $role) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-body">
          @if($errors->any())
            <div class="alert alert-danger">
              <ul class="mb-0">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="form-group">
            <label for="name">Nombre del Rol *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name', $role->name) }}" required>
          </div>

          <div class="form-group">
            <label>Permisos</label>
            <div class="row">
              @foreach($permissions as $module => $modulePermissions)
                <div class="col-md-4">
                  <div class="card">
                    <div class="card-header bg-light">
                      <h6 class="mb-0">{{ ucfirst($module) }}</h6>
                    </div>
                    <div class="card-body">
                      @foreach($modulePermissions as $permission)
                        <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" type="checkbox" 
                                 id="perm_{{ $permission->id }}" 
                                 name="permissions[]" value="{{ $permission->id }}" 
                                 {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                          <label class="custom-control-label" for="perm_{{ $permission->id }}">
                            {{ $permission->name }}
                          </label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Rol</button>
          <a href="{{ route('roles.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
