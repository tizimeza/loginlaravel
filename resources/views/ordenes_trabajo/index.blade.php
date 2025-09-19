@extends('layouts.admin')

@section('title', 'Gestión de Órdenes de Trabajo')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Órdenes de Trabajo</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-clipboard-list mr-1"></i>
          Lista de Órdenes de Trabajo
        </h3>
        <div class="card-tools">
          <a href="{{ route('ordenes_trabajo.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nueva Orden
          </a>
        </div>
      </div>

      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
            {{ session('success') }}
          </div>
        @endif

        <!-- Filtros -->
        <div class="row mb-3">
          <div class="col-md-12">
            <form method="GET" action="{{ route('ordenes_trabajo.index') }}" class="form-inline">
              <div class="form-group mr-2">
                <label for="search" class="sr-only">Buscar</label>
                <input type="text" class="form-control form-control-sm" name="search" id="search" 
                       placeholder="Buscar por número, cliente..." value="{{ request('search') }}">
              </div>
              
              <div class="form-group mr-2">
                <select name="estado" class="form-control form-control-sm">
                  <option value="">Todos los estados</option>
                  @foreach($estados as $key => $estado)
                    <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                      {{ $estado }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mr-2">
                <select name="prioridad" class="form-control form-control-sm">
                  <option value="">Todas las prioridades</option>
                  @foreach($prioridades as $key => $prioridad)
                    <option value="{{ $key }}" {{ request('prioridad') == $key ? 'selected' : '' }}>
                      {{ $prioridad }}
                    </option>
                  @endforeach
                </select>
              </div>

              <button type="submit" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-search"></i> Filtrar
              </button>
              
              <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Limpiar
              </a>
            </form>
          </div>
        </div>

        @if($ordenes->count() > 0)
          <div class="table-responsive">
            <table id="ordenesTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Nº Orden</th>
                  <th>Cliente</th>
                  <th>Vehículo</th>
                  <th>Estado</th>
                  <th>Prioridad</th>
                  <th>Fecha Ingreso</th>
                  <th>Fecha Est. Entrega</th>
                  <th>Técnico</th>
                  <th>Costo Est.</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ordenes as $orden)
                <tr class="{{ ($orden->fecha_ingreso && $orden->fecha_ingreso < now()->subDays(7)) ? 'table-warning' : '' }}">
                  <td>
                    <strong>{{ $orden->numero_orden }}</strong>
                    @if($orden->fecha_ingreso && $orden->fecha_ingreso < now()->subDays(7))
                      <br><small class="text-danger"><i class="fas fa-clock"></i> Atrasada</small>
                    @endif
                  </td>
                  <td>
                    @if($orden->cliente)
                      <strong>{{ $orden->cliente->nombre }}</strong>
                      @if($orden->cliente->telefono)
                        <br><small class="text-muted">{{ $orden->cliente->telefono }}</small>
                      @endif
                    @else
                      <span class="text-muted">Sin cliente</span>
                    @endif
                  </td>
                  <td>
                    @if($orden->vehiculo)
                      <strong>{{ $orden->vehiculo->patente }}</strong>
                      @if($orden->vehiculo->marca && $orden->vehiculo->modelo)
                        <br><small class="text-muted">
                          {{ $orden->vehiculo->marca }} {{ $orden->vehiculo->modelo }}
                        </small>
                      @endif
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-{{ $orden->estado == 'nueva' ? 'success' : ($orden->estado == 'en_proceso' ? 'warning' : ($orden->estado == 'terminada' ? 'info' : 'danger')) }}">
                      {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-{{ $orden->prioridad == 'alta' ? 'danger' : ($orden->prioridad == 'media' ? 'warning' : 'success') }}">
                      {{ ucfirst($orden->prioridad) }}
                    </span>
                  </td>
                  <td>{{ $orden->fecha_ingreso ? \Carbon\Carbon::parse($orden->fecha_ingreso)->format('d/m/Y') : 'Sin fecha' }}</td>
                  <td>
                    @if($orden->fecha_asignacion)
                      {{ \Carbon\Carbon::parse($orden->fecha_asignacion)->format('d/m/Y') }}
                    @else
                      <span class="text-muted">Sin fecha</span>
                    @endif
                  </td>
                  <td>
                    @if($orden->tecnico)
                      {{ $orden->tecnico->name }}
                    @else
                      <span class="text-muted">Sin asignar</span>
                    @endif
                  </td>
                  <td>
                    <span class="text-muted">N/A</span>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('ordenes_trabajo.show', $orden->id) }}" class="btn btn-info btn-sm" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('ordenes_trabajo.edit', $orden->id) }}" class="btn btn-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      
                      <!-- Dropdown para cambio rápido de estado -->
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" 
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Cambiar estado">
                          <i class="fas fa-exchange-alt"></i>
                        </button>
                        <div class="dropdown-menu">
                          @foreach($estados as $key => $estado)
                            @if($key !== $orden->estado)
                              <form action="{{ route('ordenes_trabajo.cambiar_estado', $orden->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="{{ $key }}">
                                <button type="submit" class="dropdown-item">
                                  <span class="badge badge-{{ \App\Models\OrdenTrabajo::getColorEstado($key) }} mr-1"></span>
                                  {{ $estado }}
                                </button>
                              </form>
                            @endif
                          @endforeach
                        </div>
                      </div>
                      
                      <form action="{{ route('ordenes_trabajo.destroy', $orden->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" 
                                onclick="return confirm('¿Estás seguro de que deseas eliminar esta orden de trabajo?')">
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

          <!-- Paginación -->
          <div class="d-flex justify-content-center mt-3">
            {{ $ordenes->appends(request()->query())->links() }}
          </div>
        @else
          <div class="text-center py-4">
            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay órdenes de trabajo registradas</h4>
            <p class="text-muted">¡Crea tu primera orden de trabajo haciendo clic en el botón "Nueva Orden"!</p>
            <a href="{{ route('ordenes_trabajo.create') }}" class="btn btn-primary">
              <i class="fas fa-plus"></i> Crear Primera Orden
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Estadísticas rápidas -->
<div class="row mt-3">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $ordenes->where('estado', 'pendiente')->count() }}</h3>
        <p>Pendientes</p>
      </div>
      <div class="icon">
        <i class="fas fa-clock"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $ordenes->where('estado', 'en_proceso')->count() }}</h3>
        <p>En Proceso</p>
      </div>
      <div class="icon">
        <i class="fas fa-cogs"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $ordenes->where('estado', 'completado')->count() }}</h3>
        <p>Completadas</p>
      </div>
      <div class="icon">
        <i class="fas fa-check"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $ordenes->where('es_atrasada', true)->count() }}</h3>
        <p>Atrasadas</p>
      </div>
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  $('#ordenesTable').DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "searching": false, // Deshabilitamos la búsqueda de DataTables ya que tenemos filtros personalizados
    "ordering": true,
    "info": true,
    "paging": false, // Deshabilitamos la paginación de DataTables ya que usamos la de Laravel
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
    }
  }).buttons().container().appendTo('#ordenesTable_wrapper .col-md-6:eq(0)');
});
</script>
@endpush
