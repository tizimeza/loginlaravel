@extends('layouts.admin')

@section('title', 'Orden de Trabajo #' . $ordenTrabajo->numero_orden)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('ordenes_trabajo.index')}}">Órdenes de Trabajo</a></li>
<li class="breadcrumb-item active">Orden #{{ $ordenTrabajo->numero_orden }}</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-clipboard-list mr-1"></i>
          Orden de Trabajo #{{ $ordenTrabajo->numero_orden }}
        </h3>
        <div class="card-tools">
          <a href="{{ route('ordenes_trabajo.edit', $ordenTrabajo) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i> Editar
          </a>
          <a href="{{ route('ordenes_trabajo.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
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

        <div class="row">
          <!-- Información General -->
          <div class="col-md-8">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-info-circle"></i> Información General</h3>
                <div class="card-tools">
                  <!-- Cambio rápido de estado -->
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-{{ $ordenTrabajo->color_estado }} dropdown-toggle" 
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      {{ $ordenTrabajo->estado_formateado }}
                    </button>
                    <div class="dropdown-menu">
                      @foreach(\App\Models\OrdenTrabajo::ESTADOS as $key => $estado)
                        @if($key !== $ordenTrabajo->estado)
                          <form action="{{ route('ordenes_trabajo.cambiar_estado', $ordenTrabajo) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="estado" value="{{ $key }}">
                            <button type="submit" class="dropdown-item">
                              {{ $estado }}
                            </button>
                          </form>
                        @endif
                      @endforeach
                    </div>
                  </div>
                </div>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <table class="table table-sm">
                      <tr>
                        <td><strong>Número de Orden:</strong></td>
                        <td>{{ $ordenTrabajo->numero_orden }}</td>
                      </tr>
                      <tr>
                        <td><strong>Estado:</strong></td>
                        <td>
                          <span class="badge badge-{{ $ordenTrabajo->color_estado }}">
                            {{ $ordenTrabajo->estado_formateado }}
                          </span>
                          @if($ordenTrabajo->es_atrasada)
                            <span class="badge badge-danger ml-1">
                              <i class="fas fa-clock"></i> Atrasada
                            </span>
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <td><strong>Prioridad:</strong></td>
                        <td>
                          <span class="badge badge-{{ $ordenTrabajo->color_prioridad }}">
                            {{ $ordenTrabajo->prioridad_formateada }}
                          </span>
                        </td>
                      </tr>
                      <tr>
                        <td><strong>Fecha de Ingreso:</strong></td>
                        <td>{{ $ordenTrabajo->fecha_ingreso->format('d/m/Y H:i') }}</td>
                      </tr>
                      <tr>
                        <td><strong>Fecha Est. Entrega:</strong></td>
                        <td>
                          @if($ordenTrabajo->fecha_estimada_entrega)
                            {{ $ordenTrabajo->fecha_estimada_entrega->format('d/m/Y H:i') }}
                          @else
                            <span class="text-muted">Sin fecha estimada</span>
                          @endif
                        </td>
                      </tr>
                      @if($ordenTrabajo->fecha_entrega_real)
                        <tr>
                          <td><strong>Fecha de Entrega Real:</strong></td>
                          <td>{{ $ordenTrabajo->fecha_entrega_real->format('d/m/Y H:i') }}</td>
                        </tr>
                      @endif
                    </table>
                  </div>
                  <div class="col-md-6">
                    <table class="table table-sm">
                      <tr>
                        <td><strong>Técnico Asignado:</strong></td>
                        <td>
                          @if($ordenTrabajo->tecnico)
                            {{ $ordenTrabajo->tecnico->name }}
                          @else
                            <span class="text-muted">Sin asignar</span>
                          @endif
                        </td>
                      </tr>
                      <tr>
                        <td><strong>Costo Estimado:</strong></td>
                        <td>
                          @if($ordenTrabajo->costo_estimado)
                            ${{ number_format($ordenTrabajo->costo_estimado, 2) }}
                          @else
                            <span class="text-muted">No estimado</span>
                          @endif
                        </td>
                      </tr>
                      @if($ordenTrabajo->costo_final)
                        <tr>
                          <td><strong>Costo Final:</strong></td>
                          <td>${{ number_format($ordenTrabajo->costo_final, 2) }}</td>
                        </tr>
                      @endif
                      <tr>
                        <td><strong>Creado:</strong></td>
                        <td>{{ $ordenTrabajo->created_at->format('d/m/Y H:i') }}</td>
                      </tr>
                      <tr>
                        <td><strong>Última Actualización:</strong></td>
                        <td>{{ $ordenTrabajo->updated_at->format('d/m/Y H:i') }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- Información del Cliente -->
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-user"></i> Información del Cliente</h3>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-md-4">
                    <strong>Nombre:</strong><br>
                    {{ $ordenTrabajo->cliente_nombre }}
                  </div>
                  <div class="col-md-4">
                    <strong>Teléfono:</strong><br>
                    @if($ordenTrabajo->cliente_telefono)
                      <a href="tel:{{ $ordenTrabajo->cliente_telefono }}">{{ $ordenTrabajo->cliente_telefono }}</a>
                    @else
                      <span class="text-muted">No proporcionado</span>
                    @endif
                  </div>
                  <div class="col-md-4">
                    <strong>Email:</strong><br>
                    @if($ordenTrabajo->cliente_email)
                      <a href="mailto:{{ $ordenTrabajo->cliente_email }}">{{ $ordenTrabajo->cliente_email }}</a>
                    @else
                      <span class="text-muted">No proporcionado</span>
                    @endif
                  </div>
                </div>
              </div>
            </div>

            <!-- Descripción del Problema -->
            <div class="card card-outline card-warning">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Descripción del Problema</h3>
              </div>
              <div class="card-body">
                <p>{{ $ordenTrabajo->descripcion_problema }}</p>
                
                @if($ordenTrabajo->observaciones)
                  <hr>
                  <strong>Observaciones:</strong>
                  <p class="text-muted">{{ $ordenTrabajo->observaciones }}</p>
                @endif
              </div>
            </div>

            <!-- Tareas Relacionadas -->
            @if($ordenTrabajo->tareas->count() > 0)
              <div class="card card-outline card-secondary">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-tasks"></i> Tareas Relacionadas</h3>
                </div>
                <div class="card-body">
                  <div class="list-group">
                    @foreach($ordenTrabajo->tareas as $tarea)
                      <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                          <h6 class="mb-1">{{ $tarea->titulo }}</h6>
                          <p class="mb-1">{{ $tarea->nombre }}</p>
                          @if($tarea->user)
                            <small class="text-muted">Asignado a: {{ $tarea->user->name }}</small>
                          @endif
                        </div>
                        <span class="badge badge-{{ $tarea->completada ? 'success' : 'warning' }} badge-pill">
                          {{ $tarea->completada ? 'Completada' : 'Pendiente' }}
                        </span>
                      </div>
                    @endforeach
                  </div>
                </div>
              </div>
            @endif
          </div>

          <!-- Información del Vehículo -->
          <div class="col-md-4">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-car"></i> Información del Vehículo</h3>
              </div>
              <div class="card-body">
                @if($ordenTrabajo->vehiculo)
                  @if($ordenTrabajo->vehiculo->imagen)
                    <div class="text-center mb-3">
                      <img src="{{ asset('storage/' . $ordenTrabajo->vehiculo->imagen) }}" 
                           alt="Imagen del vehículo" 
                           class="img-fluid rounded" 
                           style="max-height: 200px;">
                    </div>
                  @endif

                  <table class="table table-sm">
                    <tr>
                      <td><strong>Patente:</strong></td>
                      <td>{{ $ordenTrabajo->vehiculo->patente }}</td>
                    </tr>
                    @if($ordenTrabajo->vehiculo->modelo && $ordenTrabajo->vehiculo->modelo->marca)
                      <tr>
                        <td><strong>Marca:</strong></td>
                        <td>{{ $ordenTrabajo->vehiculo->modelo->marca->nombre }}</td>
                      </tr>
                      <tr>
                        <td><strong>Modelo:</strong></td>
                        <td>{{ $ordenTrabajo->vehiculo->modelo->nombre }}</td>
                      </tr>
                    @endif
                    <tr>
                      <td><strong>Color:</strong></td>
                      <td>
                        <span class="badge badge-info">{{ $ordenTrabajo->vehiculo->color }}</span>
                      </td>
                    </tr>
                    <tr>
                      <td><strong>Año:</strong></td>
                      <td>{{ $ordenTrabajo->vehiculo->anio }}</td>
                    </tr>
                    <tr>
                      <td><strong>Registrado:</strong></td>
                      <td>{{ $ordenTrabajo->vehiculo->created_at->format('d/m/Y') }}</td>
                    </tr>
                  </table>

                  <div class="text-center">
                    <a href="{{ route('vehiculos.show', $ordenTrabajo->vehiculo) }}" class="btn btn-info btn-sm">
                      <i class="fas fa-eye"></i> Ver Detalles del Vehículo
                    </a>
                  </div>
                @else
                  <p class="text-muted">No hay información del vehículo disponible.</p>
                @endif
              </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card card-outline card-dark">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-tools"></i> Acciones</h3>
              </div>
              <div class="card-body">
                <div class="d-grid gap-2">
                  <a href="{{ route('ordenes_trabajo.edit', $ordenTrabajo) }}" class="btn btn-warning btn-block">
                    <i class="fas fa-edit"></i> Editar Orden
                  </a>
                  
                  <button type="button" class="btn btn-info btn-block" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimir
                  </button>
                  
                  @if($ordenTrabajo->cliente_email)
                    <a href="mailto:{{ $ordenTrabajo->cliente_email }}?subject=Orden de Trabajo #{{ $ordenTrabajo->numero_orden }}" 
                       class="btn btn-secondary btn-block">
                      <i class="fas fa-envelope"></i> Enviar Email
                    </a>
                  @endif
                  
                  <hr>
                  
                  <form action="{{ route('ordenes_trabajo.destroy', $ordenTrabajo) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-block" 
                            onclick="return confirm('¿Estás seguro de que deseas eliminar esta orden de trabajo?')">
                      <i class="fas fa-trash"></i> Eliminar Orden
                    </button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Mejorar la experiencia de impresión
window.addEventListener('beforeprint', function() {
  document.title = 'Orden de Trabajo #{{ $ordenTrabajo->numero_orden }}';
});

// Confirmar cambios de estado
$('form[action*="cambiar_estado"]').on('submit', function(e) {
  const estadoNuevo = $(this).find('input[name="estado"]').val();
  const confirmacion = confirm(`¿Confirmar cambio de estado a "${estadoNuevo}"?`);
  
  if (!confirmacion) {
    e.preventDefault();
  }
});
</script>
@endpush
