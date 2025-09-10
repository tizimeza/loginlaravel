
@extends('layouts.admin')

@section('title', 'Detalles del Vehículo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('vehiculos.index')}}">Vehículos</a></li>
<li class="breadcrumb-item active">{{ $vehiculo->patente }}</li>
@endsection

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-car mr-1"></i>
          Detalles del Vehículo: {{ $vehiculo->patente }}
        </h3>
        <div class="card-tools">
          <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i> Editar
          </a>
        </div>
      </div>

      <div class="card-body">
        @if($vehiculo->imagen)
          <div class="row mb-4">
            <div class="col-12 text-center">
              <img src="{{ asset('storage/' . $vehiculo->imagen) }}" 
                   alt="Imagen del vehículo {{ $vehiculo->patente }}" 
                   class="img-fluid rounded shadow-sm"
                   style="max-height: 300px; max-width: 100%; object-fit: cover;">
            </div>
          </div>
        @endif

        <div class="row">
          <div class="col-md-6">
            <table class="table table-borderless">
              <tr>
                <th width="40%">Patente:</th>
                <td>
                  <span class="badge badge-primary badge-lg">{{ $vehiculo->patente }}</span>
                </td>
              </tr>
              <tr>
                <th>Color:</th>
                <td>
                  <span class="badge badge-info">{{ $vehiculo->color }}</span>
                </td>
              </tr>
              <tr>
                <th>Año:</th>
                <td>
                  <strong>{{ $vehiculo->anio }}</strong>
                </td>
              </tr>
            </table>
          </div>
          
          <div class="col-md-6">
            <table class="table table-borderless">
              @if ($vehiculo->modelo)
                <tr>
                  <th width="40%">Modelo:</th>
                  <td>{{ $vehiculo->modelo->nombre }}</td>
                </tr>
                
                @if ($vehiculo->modelo->marca)
                  <tr>
                    <th>Marca:</th>
                    <td>
                      <strong>{{ $vehiculo->modelo->marca->nombre }}</strong>
                    </td>
                  </tr>
                @endif
              @else
                <tr>
                  <th>Modelo:</th>
                  <td><span class="text-muted">No asignado</span></td>
                </tr>
              @endif
            </table>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <div class="row">
          <div class="col-md-6">
            <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning">
              <i class="fas fa-edit"></i> Editar Vehículo
            </a>
          </div>
          <div class="col-md-6 text-right">
            <a href="{{ route('vehiculos.index') }}" class="btn btn-secondary">
              <i class="fas fa-arrow-left"></i> Volver al Listado
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <!-- Información del registro -->
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-info-circle"></i>
          Información del Registro
        </h3>
      </div>
      <div class="card-body">
        <table class="table table-sm table-borderless">
          <tr>
            <th>ID:</th>
            <td>{{ $vehiculo->id }}</td>
          </tr>
          <tr>
            <th>Creado:</th>
            <td>
              <small class="text-muted">
                {{ $vehiculo->created_at->format('d/m/Y') }}<br>
                {{ $vehiculo->created_at->format('H:i:s') }}
              </small>
            </td>
          </tr>
          <tr>
            <th>Actualizado:</th>
            <td>
              <small class="text-muted">
                {{ $vehiculo->updated_at->format('d/m/Y') }}<br>
                {{ $vehiculo->updated_at->format('H:i:s') }}
              </small>
            </td>
          </tr>
        </table>
      </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="card card-secondary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-cogs"></i>
          Acciones Rápidas
        </h3>
      </div>
      <div class="card-body">
        <div class="d-grid gap-2">
          <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning btn-block">
            <i class="fas fa-edit"></i> Editar Vehículo
          </a>
          <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger btn-block" 
                    onclick="return confirm('¿Estás seguro de que deseas eliminar este vehículo?')">
              <i class="fas fa-trash"></i> Eliminar Vehículo
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection