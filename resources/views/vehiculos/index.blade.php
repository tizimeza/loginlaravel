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
        <h3>{{ $vehiculos->where('estado', 'disponible')->count() }}</h3>
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
        <h3>{{ $vehiculos->where('estado', 'en_uso')->count() }}</h3>
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
        <h3>{{ $vehiculos->where('estado', 'mantenimiento')->count() }}</h3>
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
        <h3>{{ $vehiculos->where('estado', 'fuera_servicio')->count() }}</h3>
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
          <div class="table-responsive">
            <table id="vehiculosTable" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Patente</th>
                  <th>Tipo</th>
                  <th>Marca/Modelo</th>
                  <th>Estado</th>
                  <th>Capacidad</th>
                  <th>Kilometraje</th>
                  <th>VTV</th>
                  <th>Alertas</th>
                  <th>Acciones</th>
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
                  <td>
                    <strong>{{ $vehiculo->marca }}</strong>
                    <br>
                    <small class="text-muted">{{ $vehiculo->modelo }}</small>
                  </td>
                  <td>
                    <span class="badge badge-{{ $vehiculo->color_estado }}">
                      {{ $vehiculo->estado_formateado }}
                    </span>
                  </td>
                  <td>
                    {{ number_format($vehiculo->capacidad_carga) }} kg
                    <br>
                    <small class="text-muted">{{ $vehiculo->combustible }}</small>
                  </td>
                  <td>
                    {{ number_format($vehiculo->kilometraje) }} km
                  </td>
                  <td>
                    @if($vehiculo->fecha_vencimiento_vtv)
                      {{ $vehiculo->fecha_vencimiento_vtv->format('m/Y') }}
                      @if($vehiculo->necesitaVTV())
                        <br><span class="badge badge-warning">Próxima</span>
                      @endif
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    @if(count($vehiculo->alertas) > 0)
                      @foreach($vehiculo->alertas as $alerta)
                        <span class="badge badge-warning">{{ $alerta }}</span><br>
                      @endforeach
                    @else
                      <span class="text-success"><i class="fas fa-check"></i> OK</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('vehiculos.show', $vehiculo) }}" class="btn btn-info btn-sm" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('vehiculos.edit', $vehiculo) }}" class="btn btn-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      <form action="{{ route('vehiculos.destroy', $vehiculo) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar" 
                                onclick="return confirm('¿Estás seguro de que deseas eliminar este vehículo?')">
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
            {{ $vehiculos->links() }}
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  $('#vehiculosTable').DataTable({
    "responsive": true,
    "lengthChange": false,
    "autoWidth": false,
    "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Spanish.json"
    }
  }).buttons().container().appendTo('#vehiculosTable_wrapper .col-md-6:eq(0)');
});
</script>
@endpush
