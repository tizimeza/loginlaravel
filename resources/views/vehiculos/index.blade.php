@extends('layouts.admin')

@section('title', 'Furgonetas TecnoServi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Furgonetas</li>
@endsection

@section('content')
<!-- Resumen de Furgonetas -->
<div class="row mb-4">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>{{ $stats['disponibles'] }}</h3>
        <p>Disponibles</p>
      </div>
      <div class="icon">
        <i class="fas fa-truck"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>{{ $stats['en_uso'] }}</h3>
        <p>En Uso</p>
      </div>
      <div class="icon">
        <i class="fas fa-truck-loading"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $stats['mantenimiento'] }}</h3>
        <p>Mantenimiento</p>
      </div>
      <div class="icon">
        <i class="fas fa-wrench"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $stats['fuera_servicio'] }}</h3>
        <p>Fuera de Servicio</p>
      </div>
      <div class="icon">
        <i class="fas fa-times-circle"></i>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-truck mr-1"></i>
          Furgonetas de TecnoServi
        </h3>
        <div class="card-tools">
          @if(auth()->user()->hasAnyRole(['admin', 'supervisor']))
            <a href="{{ route('reportes.vehiculos-pdf') }}" class="btn btn-danger btn-sm" target="_blank">
              <i class="fas fa-file-pdf"></i> Exportar PDF
            </a>
          @endif
          <a href="{{ route('vehiculos.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo Vehículo
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

        @if($vehiculos->count() > 0)
          <!-- Filtros personalizados -->
          <div class="row mb-3">
            <div class="col-md-3">
              <div class="form-group">
                <label for="filtroEstado">Filtrar por Estado:</label>
                <select class="form-control form-control-sm" id="filtroEstado">
                  <option value="">Todos los estados</option>
                  <option value="Disponible">Disponible</option>
                  <option value="En Uso">En Uso</option>
                  <option value="En Mantenimiento">En Mantenimiento</option>
                  <option value="Fuera de Servicio">Fuera de Servicio</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="filtroTipo">Filtrar por Tipo:</label>
                <select class="form-control form-control-sm" id="filtroTipo">
                  <option value="">Todos los tipos</option>
                  <option value="Ford Transit">Ford Transit</option>
                  <option value="Renault Kangoo">Renault Kangoo</option>
                  <option value="Peugeot Partner">Peugeot Partner</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="filtroCombustible">Filtrar por Combustible:</label>
                <select class="form-control form-control-sm" id="filtroCombustible">
                  <option value="">Todos</option>
                  <option value="nafta">Nafta</option>
                  <option value="diesel">Diesel</option>
                  <option value="gnc">GNC</option>
                  <option value="electrico">Eléctrico</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label for="filtroAlertas">Filtrar por Alertas:</label>
                <select class="form-control form-control-sm" id="filtroAlertas">
                  <option value="">Todos</option>
                  <option value="con-alertas">Con alertas</option>
                  <option value="sin-alertas">Sin alertas</option>
                </select>
              </div>
            </div>
          </div>

          <div class="table-responsive">
            <table id="vehiculosTable" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Patente</th>
                  <th>Tipo</th>
                  <th>Marca</th>
                  <th>Modelo</th>
                  <th>Estado</th>
                  <th>Capacidad</th>
                  <th>Combustible</th>
                  <th>Kilometraje</th>
                  <th>VTV</th>
                  <th>Alertas</th>
                  <th style="width: 120px;">Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($vehiculos as $vehiculo)
                <tr>
                  <td>
                    <strong>{{ $vehiculo->patente }}</strong>
                    <br>
                    <small class="text-muted">{{ $vehiculo->anio }} - {{ $vehiculo->color }}</small>
                  </td>
                  <td>
                    <span class="badge badge-primary">{{ $vehiculo->tipo_vehiculo_formateado }}</span>
                  </td>
                  <td>{{ $vehiculo->marca }}</td>
                  <td>{{ $vehiculo->modelo }}</td>
                  <td>
                    <span class="badge badge-{{ $vehiculo->color_estado }}">
                      {{ $vehiculo->estado_formateado }}
                    </span>
                  </td>
                  <td class="text-right">{{ number_format($vehiculo->capacidad_carga) }} kg</td>
                  <td>{{ $vehiculo->combustible }}</td>
                  <td class="text-right">{{ number_format($vehiculo->kilometraje) }} km</td>
                  <td>
                    @if($vehiculo->fecha_vencimiento_vtv)
                      {{ $vehiculo->fecha_vencimiento_vtv->format('m/Y') }}
                      @if($vehiculo->necesitaVTV())
                        <br><span class="badge badge-warning badge-sm">Próxima</span>
                      @endif
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    @if(count($vehiculo->alertas) > 0)
                      @foreach($vehiculo->alertas as $alerta)
                        <span class="badge badge-warning badge-sm d-block mb-1">{{ $alerta }}</span>
                      @endforeach
                    @else
                      <span class="text-success"><i class="fas fa-check-circle"></i> OK</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-info" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-danger btn-delete"
                              data-id="{{ $vehiculo->id }}"
                              data-patente="{{ $vehiculo->patente }}"
                              title="Eliminar">
                        <i class="fas fa-trash"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @else
          <div class="text-center py-4">
            <i class="fas fa-car fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay vehículos registrados</h4>
            <p class="text-muted">¡Agrega tu primer vehículo haciendo clic en el botón "Nuevo Vehículo"!</p>
            <a href="{{ route('vehiculos.create') }}" class="btn btn-primary">
              <i class="fas fa-plus"></i> Crear Primer Vehículo
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal de confirmación de eliminación -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-danger">
        <h5 class="modal-title" id="deleteModalLabel">
          <i class="fas fa-exclamation-triangle"></i> Confirmar Eliminación
        </h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>¿Estás seguro de que deseas eliminar el vehículo <strong id="vehiculoPatente"></strong>?</p>
        <p class="text-danger"><i class="fas fa-exclamation-circle"></i> Esta acción no se puede deshacer.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times"></i> Cancelar
        </button>
        <form id="deleteForm" method="POST" style="display: inline;">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">
            <i class="fas fa-trash"></i> Eliminar Vehículo
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Inicializar DataTable
  var table = $('#vehiculosTable').DataTable({
    "responsive": true,
    "lengthChange": true,
    "autoWidth": false,
    "pageLength": 10,
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]],
    "order": [[0, 'asc']], // Ordenar por patente
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
    },
    "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
    "columnDefs": [
      { "orderable": false, "targets": [9, 10] }, // Alertas y Acciones no ordenables
      { "searchable": false, "targets": [10] } // Acciones no buscables
    ]
  });

  // Filtro personalizado por estado
  $('#filtroEstado').on('change', function() {
    table.column(4).search(this.value).draw();
  });

  // Filtro personalizado por tipo
  $('#filtroTipo').on('change', function() {
    table.column(1).search(this.value).draw();
  });

  // Filtro personalizado por combustible
  $('#filtroCombustible').on('change', function() {
    table.column(6).search(this.value).draw();
  });

  // Filtro personalizado por alertas
  $('#filtroAlertas').on('change', function() {
    var value = this.value;
    if (value === 'con-alertas') {
      // Buscar filas que NO contengan "OK" en la columna de alertas
      $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
          return data[9].indexOf('OK') === -1;
        }
      );
    } else if (value === 'sin-alertas') {
      // Buscar filas que contengan "OK" en la columna de alertas
      $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
          return data[9].indexOf('OK') !== -1;
        }
      );
    } else {
      // Limpiar filtro
      $.fn.dataTable.ext.search.pop();
    }
    table.draw();
  });

  // Modal de eliminación
  $('.btn-delete').on('click', function() {
    var vehiculoId = $(this).data('id');
    var vehiculoPatente = $(this).data('patente');

    $('#vehiculoPatente').text(vehiculoPatente);
    $('#deleteForm').attr('action', '/vehiculos/' + vehiculoId);

    $('#deleteModal').modal('show');
  });

  // Cerrar modal al hacer submit
  $('#deleteForm').on('submit', function() {
    $('#deleteModal').modal('hide');
  });
});
</script>
@endpush
