@extends('layouts.admin')

@section('title', 'Detalle del Cliente')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('clientes.index')}}">Clientes</a></li>
<li class="breadcrumb-item active">{{ $cliente->nombre }}</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Información Principal del Cliente -->
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-user mr-1"></i>
          Información del Cliente
        </h3>
        <div class="card-tools">
          <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i> Editar
          </a>
          <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <dl class="row">
              <dt class="col-sm-3">Nombre:</dt>
              <dd class="col-sm-9">
                <h5 class="mb-0">{{ $cliente->nombre }}</h5>
              </dd>

              <dt class="col-sm-3">Tipo:</dt>
              <dd class="col-sm-9">
                <span class="badge badge-{{ $cliente->color_tipo }} badge-lg">
                  {{ $cliente->tipo_cliente_formateado }}
                </span>
                @if($cliente->es_premium)
                  <span class="badge badge-warning ml-2">
                    <i class="fas fa-star"></i> Premium
                  </span>
                @endif
              </dd>

              <dt class="col-sm-3">Estado:</dt>
              <dd class="col-sm-9">
                @if($cliente->activo)
                  <span class="badge badge-success">Activo</span>
                @else
                  <span class="badge badge-secondary">Inactivo</span>
                @endif
              </dd>

              @if($cliente->documento)
                <dt class="col-sm-3">Documento:</dt>
                <dd class="col-sm-9">{{ $cliente->documento }}</dd>
              @endif

              <dt class="col-sm-3">Dirección:</dt>
              <dd class="col-sm-9">{{ $cliente->direccion }}</dd>

              @if($cliente->observaciones)
                <dt class="col-sm-3">Observaciones:</dt>
                <dd class="col-sm-9">{{ $cliente->observaciones }}</dd>
              @endif
            </dl>
          </div>

          <div class="col-md-4">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h5 class="card-title mb-0">
                  <i class="fas fa-address-book"></i> Contacto
                </h5>
              </div>
              <div class="card-body p-2">
                <p class="mb-1">
                  <i class="fas fa-phone text-primary"></i>
                  <strong>Teléfono:</strong><br>
                  {{ $cliente->telefono }}
                </p>

                @if($cliente->email)
                  <p class="mb-0">
                    <i class="fas fa-envelope text-info"></i>
                    <strong>Email:</strong><br>
                    <a href="mailto:{{ $cliente->email }}">{{ $cliente->email }}</a>
                  </p>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Órdenes de Trabajo del Cliente -->
    <div class="card card-success">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-tools mr-1"></i>
          Órdenes de Trabajo ({{ $cliente->ordenesTrabajos->count() }})
        </h3>
        <div class="card-tools">
          <a href="{{ route('ordenes_trabajo.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-success btn-sm">
            <i class="fas fa-plus"></i> Nueva Orden
          </a>
        </div>
      </div>

      <div class="card-body">
        @if($cliente->ordenesTrabajos->count() > 0)
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>N° Orden</th>
                  <th>Descripción</th>
                  <th>Estado</th>
                  <th>Fecha Ingreso</th>
                  <th>Prioridad</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($cliente->ordenesTrabajos->take(10) as $orden)
                  <tr>
                    <td>
                      <strong>{{ $orden->numero_orden }}</strong>
                    </td>
                    <td>{{ Str::limit($orden->descripcion_problema, 50) }}</td>
                    <td>
                      <span class="badge badge-{{ $orden->estado === 'terminada' ? 'success' : ($orden->estado === 'en_proceso' ? 'primary' : 'secondary') }}">
                        {{ $orden->estado }}
                      </span>
                    </td>
                    <td>{{ $orden->fecha_ingreso ? $orden->fecha_ingreso->format('d/m/Y') : 'N/A' }}</td>
                    <td>
                      <span class="badge badge-{{ $orden->prioridad === 'alta' ? 'danger' : ($orden->prioridad === 'media' ? 'warning' : 'info') }}">
                        {{ $orden->prioridad }}
                      </span>
                    </td>
                    <td>
                      <a href="{{ route('ordenes_trabajo.show', $orden) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          @if($cliente->ordenesTrabajos->count() > 10)
            <div class="text-center mt-3">
              <a href="{{ route('ordenes_trabajo.index', ['cliente' => $cliente->id]) }}" class="btn btn-outline-primary">
                Ver todas las órdenes ({{ $cliente->ordenesTrabajos->count() }})
              </a>
            </div>
          @endif
        @else
          <div class="text-center py-4">
            <i class="fas fa-tools fa-3x text-muted mb-3"></i>
            <h5>No hay órdenes de trabajo</h5>
            <p class="text-muted">Este cliente aún no tiene órdenes de trabajo registradas.</p>
            <a href="{{ route('ordenes_trabajo.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-success">
              <i class="fas fa-plus"></i> Crear Primera Orden
            </a>
          </div>
        @endif
      </div>
    </div>

    <!-- Estadísticas del Cliente -->
    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-warning">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="fas fa-chart-bar"></i> Estadísticas
            </h5>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="description-block">
                  <h5 class="description-header">{{ $cliente->ordenesTrabajos->where('estado', 'terminada')->count() }}</h5>
                  <span class="description-text">ÓRDENES TERMINADAS</span>
                </div>
              </div>
              <div class="col-6">
                <div class="description-block">
                  <h5 class="description-header">{{ $cliente->ordenesTrabajos->where('estado', 'en_proceso')->count() }}</h5>
                  <span class="description-text">ÓRDENES EN PROCESO</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card card-outline card-danger">
          <div class="card-header">
            <h5 class="card-title mb-0">
              <i class="fas fa-clock"></i> Información Temporal
            </h5>
          </div>
          <div class="card-body">
            <dl class="row mb-0">
              <dt class="col-sm-6">Cliente desde:</dt>
              <dd class="col-sm-6">{{ $cliente->created_at->format('d/m/Y') }}</dd>

              <dt class="col-sm-6">Última actualización:</dt>
              <dd class="col-sm-6">{{ $cliente->updated_at->diffForHumans() }}</dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
