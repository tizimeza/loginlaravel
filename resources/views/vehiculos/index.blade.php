@extends('layouts.admin')

@section('title', 'Gestión de Vehículos')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Vehículos</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-car mr-1"></i>
          Lista de Vehículos
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
                  <th>ID</th>
                  <th>Imagen</th>
                  <th>Patente</th>
                  <th>Marca</th>
                  <th>Modelo</th>
                  <th>Color</th>
                  <th>Año</th>
                  <th>Fecha Registro</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($vehiculos as $vehiculo)
                <tr>
                  <td>{{ $vehiculo->id }}</td>
                  <td>
                    @if($vehiculo->imagen)
                      <img src="{{ asset('storage/' . $vehiculo->imagen) }}" 
                           alt="Imagen del vehículo" 
                           class="img-thumbnail" 
                           style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                      <div class="text-center text-muted" style="width: 50px; height: 50px; line-height: 50px; background-color: #f8f9fa; border-radius: 4px;">
                        <i class="fas fa-car"></i>
                      </div>
                    @endif
                  </td>
                  <td>
                    <strong>{{ $vehiculo->patente }}</strong>
                  </td>
                  <td>
                    @if($vehiculo->modelo && $vehiculo->modelo->marca)
                      {{ $vehiculo->modelo->marca->nombre }}
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    @if($vehiculo->modelo)
                      {{ $vehiculo->modelo->nombre }}
                    @else
                      <span class="text-muted">N/A</span>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-info">{{ $vehiculo->color }}</span>
                  </td>
                  <td>{{ $vehiculo->anio }}</td>
                  <td>{{ $vehiculo->created_at->format('d/m/Y H:i') }}</td>
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
