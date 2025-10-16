@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Estadísticas principales -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $stats['total_ordenes'] }}</h3>
        <p>Total Órdenes</p>
      </div>
      <div class="icon">
        <i class="fas fa-clipboard-list"></i>
      </div>
      <a href="{{ route('ordenes_trabajo.index') }}" class="small-box-footer">
        Ver órdenes <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $stats['ordenes_pendientes'] }}</h3>
        <p>Órdenes Pendientes</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
      <a href="{{ route('ordenes_trabajo.index') }}" class="small-box-footer">
        Ver pendientes <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-primary">
      <div class="inner">
        <h3>{{ $stats['ordenes_en_proceso'] }}</h3>
        <p>En Proceso</p>
      </div>
      <div class="icon">
        <i class="fas fa-cog fa-spin"></i>
      </div>
      <a href="{{ route('ordenes_trabajo.index') }}" class="small-box-footer">
        Ver en proceso <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $stats['ordenes_completadas'] }}</h3>
        <p>Completadas</p>
      </div>
      <div class="icon">
        <i class="fas fa-check-circle"></i>
      </div>
      <a href="{{ route('ordenes_trabajo.index') }}" class="small-box-footer">
        Ver completadas <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

<!-- Segunda fila de estadísticas -->
<div class="row">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-gradient-info">
      <div class="inner">
        <h3>{{ $stats['total_clientes'] }}</h3>
        <p>Clientes Activos</p>
      </div>
      <div class="icon">
        <i class="fas fa-users"></i>
      </div>
      <a href="{{ route('clientes.index') }}" class="small-box-footer">
        Gestionar clientes <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-gradient-warning">
      <div class="inner">
        <h3>{{ $stats['total_vehiculos'] }}</h3>
        <p>Flota de Vehículos</p>
      </div>
      <div class="icon">
        <i class="fas fa-car"></i>
      </div>
      <a href="{{ route('vehiculos.index') }}" class="small-box-footer">
        Ver vehículos <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box {{ $stats['productos_bajo_stock'] > 0 ? 'bg-danger' : 'bg-gradient-success' }}">
      <div class="inner">
        <h3>{{ $stats['productos_bajo_stock'] }}</h3>
        <p>Productos Stock Bajo</p>
      </div>
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
      <a href="{{ route('stock.index') }}" class="small-box-footer">
        Ver inventario <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>

  <div class="col-lg-3 col-6">
    <div class="small-box bg-gradient-primary">
      <div class="inner">
        <h3>{{ $ordenesMes }}</h3>
        <p>Órdenes del Mes</p>
      </div>
      <div class="icon">
        <i class="fas fa-calendar-alt"></i>
      </div>
      <a href="{{ route('ordenes_trabajo.index') }}" class="small-box-footer">
        Ver del mes <i class="fas fa-arrow-circle-right"></i>
      </a>
    </div>
  </div>
</div>

<div class="row">
  <!-- Órdenes Recientes -->
  <div class="col-md-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-clipboard-list mr-1"></i>
          {{ Auth::user()->hasRole('tecnico') ? 'Mis Órdenes Recientes' : 'Órdenes Recientes' }}
        </h3>
        <div class="card-tools">
          @can('crear_ordenes')
          <a href="{{ route('ordenes_trabajo.create') }}" class="btn btn-success btn-sm mr-1">
            <i class="fas fa-plus"></i> Nueva Orden
          </a>
          @endcan
          <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-list"></i> Ver Todas
          </a>
        </div>
      </div>
      <div class="card-body p-0">
        @if($ordenesRecientes->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay órdenes registradas</h5>
            @can('crear_ordenes')
            <a href="{{ route('ordenes_trabajo.create') }}" class="btn btn-primary mt-2">
              <i class="fas fa-plus"></i> Crear Primera Orden
            </a>
            @endcan
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Nº Orden</th>
                  <th>Cliente</th>
                  <th>Técnico</th>
                  <th>Vehículo</th>
                  <th>Estado</th>
                  <th>Fecha</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ordenesRecientes as $orden)
                <tr>
                  <td><strong>{{ $orden->numero_orden }}</strong></td>
                  <td>{{ $orden->cliente->nombre ?? 'Sin cliente' }}</td>
                  <td>{{ $orden->tecnico->name ?? 'Sin asignar' }}</td>
                  <td>{{ $orden->vehiculo->patente ?? 'N/A' }}</td>
                  <td>
                    @if($orden->estado == 'pendiente')
                      <span class="badge badge-warning">Pendiente</span>
                    @elseif($orden->estado == 'en_proceso')
                      <span class="badge badge-info">En Proceso</span>
                    @elseif($orden->estado == 'completado')
                      <span class="badge badge-success">Completado</span>
                    @else
                      <span class="badge badge-secondary">{{ ucfirst($orden->estado) }}</span>
                    @endif
                  </td>
                  <td>{{ $orden->created_at->format('d/m/Y') }}</td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('ordenes_trabajo.show', $orden) }}" class="btn btn-info" title="Ver">
                        <i class="fas fa-eye"></i>
                      </a>
                      @can('editar_ordenes')
                      <a href="{{ route('ordenes_trabajo.edit', $orden) }}" class="btn btn-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      @endcan
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Tercera fila: Stock Bajo y Vehículos -->
<div class="row">
  <!-- Productos con Stock Bajo -->
  <div class="col-md-6">
    <div class="card">
      <div class="card-header {{ $productosStockBajo->count() > 0 ? 'bg-danger' : 'bg-success' }}">
        <h3 class="card-title">
          <i class="fas fa-exclamation-triangle mr-1"></i>
          Alertas de Inventario
        </h3>
        <div class="card-tools">
          <a href="{{ route('stock.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-boxes"></i> Ver Inventario
          </a>
        </div>
      </div>
      <div class="card-body p-0">
        @if($productosStockBajo->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h5 class="text-success">Stock OK</h5>
            <p class="text-muted">Todos los productos tienen stock suficiente</p>
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th class="text-center">Stock Actual</th>
                  <th class="text-center">Stock Mínimo</th>
                  <th class="text-center">Estado</th>
                </tr>
              </thead>
              <tbody>
                @foreach($productosStockBajo as $producto)
                <tr>
                  <td><strong>{{ $producto->nombre }}</strong></td>
                  <td class="text-center">
                    <span class="badge badge-lg {{ ($producto->cantidad_actual ?? 0) == 0 ? 'badge-danger' : 'badge-warning' }}">
                      {{ $producto->cantidad_actual ?? 0 }}
                    </span>
                  </td>
                  <td class="text-center">{{ $producto->cantidad_minima ?? 0 }}</td>
                  <td class="text-center">
                    @if(($producto->cantidad_actual ?? 0) == 0)
                      <span class="badge badge-danger">AGOTADO</span>
                    @else
                      <span class="badge badge-warning">BAJO</span>
                    @endif
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Estado de Vehículos -->
  @if(Auth::user()->hasAnyRole(['admin', 'supervisor']))
  <div class="col-md-6">
    <div class="card">
      <div class="card-header bg-warning">
        <h3 class="card-title">
          <i class="fas fa-car mr-1"></i>
          Estado de la Flota
        </h3>
        <div class="card-tools">
          <a href="{{ route('vehiculos.index') }}" class="btn btn-light btn-sm">
            <i class="fas fa-list"></i> Ver Todos
          </a>
        </div>
      </div>
      <div class="card-body p-0">
        @if($vehiculos->isEmpty())
          <div class="text-center py-5">
            <i class="fas fa-car fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No hay vehículos registrados</h5>
            @can('crear_vehiculos')
            <a href="{{ route('vehiculos.create') }}" class="btn btn-warning mt-2">
              <i class="fas fa-plus"></i> Agregar Vehículo
            </a>
            @endcan
          </div>
        @else
          <div class="table-responsive">
            <table class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>Patente</th>
                  <th>Vehículo</th>
                  <th>Tipo</th>
                  <th class="text-center">Estado</th>
                  <th class="text-center">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($vehiculos as $vehiculo)
                <tr>
                  <td><strong>{{ $vehiculo->patente }}</strong></td>
                  <td>{{ $vehiculo->marca }} {{ $vehiculo->modelo }}</td>
                  <td>{{ $vehiculo->tipo_vehiculo_formateado ?? ucfirst($vehiculo->tipo_vehiculo) }}</td>
                  <td class="text-center">
                    @php
                      $estadoBadge = 'badge-success';
                      $estadoTexto = 'Disponible';
                      switch($vehiculo->estado) {
                        case 'en_uso':
                          $estadoBadge = 'badge-info';
                          $estadoTexto = 'En Uso';
                          break;
                        case 'mantenimiento':
                          $estadoBadge = 'badge-warning';
                          $estadoTexto = 'Mantenimiento';
                          break;
                        case 'fuera_servicio':
                          $estadoBadge = 'badge-danger';
                          $estadoTexto = 'Fuera Servicio';
                          break;
                      }
                    @endphp
                    <span class="badge {{ $estadoBadge }}">{{ $estadoTexto }}</span>
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-info" title="Ver">
                        <i class="fas fa-eye"></i>
                      </a>
                      @can('editar_vehiculos')
                      <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      @endcan
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
  @endif
</div>

<!-- Botones de Reportes PDF -->
@if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header bg-dark">
        <h3 class="card-title">
          <i class="fas fa-file-pdf mr-1"></i>
          Reportes Ejecutivos
        </h3>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-3">
            <a href="{{ route('reportes.inventario-pdf') }}" class="btn btn-success btn-block btn-lg" target="_blank">
              <i class="fas fa-boxes fa-2x d-block mb-2"></i>
              <strong>Inventario</strong>
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('reportes.clientes-pdf') }}" class="btn btn-primary btn-block btn-lg" target="_blank">
              <i class="fas fa-users fa-2x d-block mb-2"></i>
              <strong>Clientes</strong>
            </a>
          </div>
          <div class="col-md-3">
            <a href="{{ route('reportes.vehiculos-pdf') }}" class="btn btn-warning btn-block btn-lg" target="_blank">
              <i class="fas fa-car fa-2x d-block mb-2"></i>
              <strong>Vehículos</strong>
            </a>
          </div>
          <div class="col-md-3">
            <button class="btn btn-info btn-block btn-lg" data-toggle="modal" data-target="#reportePeriodoModal">
              <i class="fas fa-calendar-alt fa-2x d-block mb-2"></i>
              <strong>Órdenes por Periodo</strong>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para Reporte por Periodo -->
<div class="modal fade" id="reportePeriodoModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h5 class="modal-title text-white">
          <i class="fas fa-calendar-alt"></i> Reporte de Órdenes por Periodo
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('reportes.ordenes-periodo-pdf') }}" method="GET" target="_blank">
        <div class="modal-body">
          <div class="form-group">
            <label><i class="fas fa-calendar"></i> Fecha Desde</label>
            <input type="date" name="fecha_desde" class="form-control" required>
          </div>
          <div class="form-group">
            <label><i class="fas fa-calendar"></i> Fecha Hasta</label>
            <input type="date" name="fecha_hasta" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-info">
            <i class="fas fa-file-pdf"></i> Generar PDF
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

@endsection
