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
          <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5><i class="icon fas fa-check"></i> ¡Éxito!</h5>
            {{ session('success') }}
          </div>
        @endif

        <!-- Filtros -->
        <div class="card card-outline card-info mb-3">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-filter"></i> Filtros de Búsqueda</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
            </div>
          </div>
          <div class="card-body">
            <form method="GET" action="{{ route('ordenes_trabajo.index') }}">
              <div class="row">
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="search">Buscar</label>
                    <input type="text" class="form-control form-control-sm" name="search" id="search"
                           placeholder="Número de orden, cliente..." value="{{ request('search') }}">
                    <small class="form-text text-muted">Busca por número, cliente o descripción</small>
                  </div>
                </div>

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="tipo_servicio">Tipo de Servicio</label>
                    <select name="tipo_servicio" id="tipo_servicio" class="form-control form-control-sm">
                      <option value="">Todos los tipos</option>
                      @foreach($tiposServicio as $key => $tipo)
                        <option value="{{ $key }}" {{ request('tipo_servicio') == $key ? 'selected' : '' }}>
                          {{ $tipo }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="estado">Estado</label>
                    <select name="estado" id="estado" class="form-control form-control-sm">
                      <option value="">Todos</option>
                      @foreach($estados as $key => $estado)
                        <option value="{{ $key }}" {{ request('estado') == $key ? 'selected' : '' }}>
                          {{ $estado }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label for="prioridad">Prioridad</label>
                    <select name="prioridad" id="prioridad" class="form-control form-control-sm">
                      <option value="">Todas</option>
                      @foreach($prioridades as $key => $prioridad)
                        <option value="{{ $key }}" {{ request('prioridad') == $key ? 'selected' : '' }}>
                          {{ $prioridad }}
                        </option>
                      @endforeach
                    </select>
                  </div>
                </div>

                <div class="col-md-2">
                  <div class="form-group">
                    <label>&nbsp;</label>
                    <div>
                      <button type="submit" class="btn btn-info btn-sm btn-block">
                        <i class="fas fa-search"></i> Filtrar
                      </button>
                      <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary btn-sm btn-block">
                        <i class="fas fa-times"></i> Limpiar
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

        @if($ordenes->count() > 0)
          <div class="table-responsive">
            <table id="ordenesTable" class="table table-bordered table-hover table-sm">
              <thead class="thead-light">
                <tr>
                  <th style="width: 100px;">Nº Orden</th>
                  <th style="width: 150px;">Tipo Servicio</th>
                  <th>Cliente</th>
                  <th style="width: 120px;">Estado</th>
                  <th style="width: 100px;">Prioridad</th>
                  <th style="width: 110px;">F. Ingreso</th>
                  <th style="width: 110px;">F. Est. Entrega</th>
                  <th style="width: 100px;">Costo Est.</th>
                  <th style="width: 180px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($ordenes as $orden)
                <tr class="{{ ($orden->fecha_ingreso && $orden->fecha_ingreso->lt(now()->subDays(7)) && $orden->estado != 'completado' && $orden->estado != 'entregado') ? 'table-warning' : '' }}">
                  <td>
                    <strong>{{ $orden->numero_orden }}</strong>
                    @if($orden->fecha_ingreso && $orden->fecha_ingreso->lt(now()->subDays(7)) && $orden->estado != 'completado' && $orden->estado != 'entregado')
                      <br><small class="badge badge-danger"><i class="fas fa-clock"></i> Atrasada</small>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-primary badge-pill">
                      {{ $orden->tipo_servicio_formateado }}
                    </span>
                  </td>
                  <td>
                    @if($orden->cliente)
                      <strong>{{ $orden->cliente->nombre }}</strong>
                      @if($orden->cliente->telefono)
                        <br><small class="text-muted"><i class="fas fa-phone"></i> {{ $orden->cliente->telefono }}</small>
                      @endif
                    @elseif($orden->cliente_telefono)
                      <small class="text-muted"><i class="fas fa-phone"></i> {{ $orden->cliente_telefono }}</small>
                      @if($orden->cliente_email)
                        <br><small class="text-muted"><i class="fas fa-envelope"></i> {{ $orden->cliente_email }}</small>
                      @endif
                    @else
                      <span class="text-muted">Sin información</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-{{ $orden->color_estado }}">
                      {{ $orden->estado_formateado }}
                    </span>
                  </td>
                  <td>
                    <span class="badge badge-{{ $orden->color_prioridad }}">
                      {{ $orden->prioridad_formateada }}
                    </span>
                  </td>
                  <td>{{ $orden->fecha_ingreso ? $orden->fecha_ingreso->format('d/m/Y') : '-' }}</td>
                  <td>
                    @if($orden->fecha_estimada_entrega)
                      {{ $orden->fecha_estimada_entrega->format('d/m/Y') }}
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    @if($orden->costo_estimado)
                      ${{ number_format($orden->costo_estimado, 2) }}
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('ordenes_trabajo.show', $orden->id) }}" class="btn btn-info" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('ordenes_trabajo.edit', $orden->id) }}" class="btn btn-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      <a href="{{ route('reportes.orden-trabajo-pdf', $orden->id) }}" class="btn btn-danger" title="Exportar PDF" target="_blank">
                        <i class="fas fa-file-pdf"></i>
                      </a>

                      <!-- Dropdown para cambio rápido de estado -->
                      <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-secondary dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Cambiar estado">
                          <i class="fas fa-exchange-alt"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                          <h6 class="dropdown-header">Cambiar Estado</h6>
                          @foreach($estados as $key => $estado)
                            @if($key !== $orden->estado)
                              <form action="{{ route('ordenes_trabajo.cambiar_estado', $orden->id) }}" method="POST" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="estado" value="{{ $key }}">
                                <button type="submit" class="dropdown-item">
                                  <i class="fas fa-circle text-{{ App\Models\OrdenTrabajo::getColorEstado($key) }} mr-1"></i>
                                  {{ $estado }}
                                </button>
                              </form>
                            @endif
                          @endforeach
                        </div>
                      </div>

                      <form action="{{ route('ordenes_trabajo.destroy', $orden->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('¿Estás seguro de eliminar esta orden?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" title="Eliminar">
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
          <div class="row mt-3">
            <div class="col-md-5">
              <p class="text-muted">
                Mostrando <strong>{{ $ordenes->firstItem() }}</strong> a <strong>{{ $ordenes->lastItem() }}</strong> de <strong>{{ $ordenes->total() }}</strong> órdenes
              </p>
            </div>
            <div class="col-md-7">
              <div class="float-right">
                {{ $ordenes->appends(request()->query())->links('vendor.pagination.bootstrap-4-sm') }}
              </div>
            </div>
          </div>
        @else
          <div class="text-center py-5">
            <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No hay órdenes de trabajo</h4>
            @if(request()->hasAny(['search', 'estado', 'prioridad', 'tipo_servicio']))
              <p class="text-muted">No se encontraron órdenes con los filtros seleccionados.</p>
              <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Limpiar Filtros
              </a>
            @else
              <p class="text-muted">¡Crea tu primera orden de trabajo!</p>
              <a href="{{ route('ordenes_trabajo.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Crear Primera Orden
              </a>
            @endif
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection

@push('styles')
<!-- DataTables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap4.min.css">
<style>
/* Estilos de paginación mejorados */
.pagination-sm .page-link {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
}

.pagination-sm .page-item:first-child .page-link {
  border-top-left-radius: 0.2rem;
  border-bottom-left-radius: 0.2rem;
}

.pagination-sm .page-item:last-child .page-link {
  border-top-right-radius: 0.2rem;
  border-bottom-right-radius: 0.2rem;
}

.pagination {
  margin-bottom: 0;
}

/* Asegurar que los botones de la tabla no se desborden */
.btn-group-sm > .btn, .btn-sm {
  padding: 0.25rem 0.5rem;
  font-size: 0.875rem;
  line-height: 1.5;
  border-radius: 0.2rem;
}
</style>
@endpush

@push('scripts')
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>

<script>
$(document).ready(function() {
  @if($ordenes->count() > 0)
  var table = $('#ordenesTable').DataTable({
    "responsive": true,
    "lengthChange": true,
    "autoWidth": false,
    "searching": false, // Usamos filtros personalizados
    "ordering": true,
    "info": false, // Deshabilitamos info porque usamos Laravel pagination
    "paging": false, // Usamos paginación de Laravel
    "order": [[5, 'desc']], // Ordenar por fecha de ingreso descendente
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json"
    },
    "dom": '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"B>>rtip',
    "buttons": [
      {
        extend: 'copy',
        text: '<i class="fas fa-copy"></i> Copiar',
        className: 'btn btn-sm btn-default',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7]
        }
      },
      {
        extend: 'csv',
        text: '<i class="fas fa-file-csv"></i> CSV',
        className: 'btn btn-sm btn-default',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7]
        }
      },
      {
        extend: 'excel',
        text: '<i class="fas fa-file-excel"></i> Excel',
        className: 'btn btn-sm btn-success',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7]
        }
      },
      {
        extend: 'pdf',
        text: '<i class="fas fa-file-pdf"></i> PDF',
        className: 'btn btn-sm btn-danger',
        orientation: 'landscape',
        pageSize: 'A4',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7]
        }
      },
      {
        extend: 'print',
        text: '<i class="fas fa-print"></i> Imprimir',
        className: 'btn btn-sm btn-default',
        exportOptions: {
          columns: [0, 1, 2, 3, 4, 5, 6, 7]
        }
      }
    ]
  });

  // Mover los botones al header de la tabla
  table.buttons().container()
    .appendTo('#ordenesTable_wrapper .col-md-6:eq(0)');
  @endif

  // Auto-submit al cambiar filtros
  $('#tipo_servicio, #estado, #prioridad').on('change', function() {
    $(this).closest('form').submit();
  });
});
</script>
@endpush
