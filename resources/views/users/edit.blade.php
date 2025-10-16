@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('users.index')}}">Usuarios</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8 offset-md-2">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fas fa-user-edit mr-1"></i> Editar Usuario: {{ $user->name }}</h3>
      </div>

      <form action="{{ route('users.update', $user) }}" method="POST">
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
            <label for="name">Nombre *</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
          </div>

          <div class="form-group">
            <label for="email">Email *</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="password">Nueva Contraseña (opcional)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                       id="password" name="password">
                <small class="form-text text-muted">Dejar en blanco para mantener la actual</small>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="password_confirmation">Confirmar Contraseña</label>
                <input type="password" class="form-control" 
                       id="password_confirmation" name="password_confirmation">
              </div>
            </div>
          </div>

          <div class="form-group">
            <label>Roles</label>
            <div>
              @foreach($roles as $role)
                <div class="custom-control custom-checkbox">
                  <input class="custom-control-input" type="checkbox" id="role_{{ $role->id }}" 
                         name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, old('roles', $userRoles)) ? 'checked' : '' }}>
                  <label class="custom-control-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Actualizar Usuario</button>
          <a href="{{ route('users.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
