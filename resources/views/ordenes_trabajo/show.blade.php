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
          <a href="{{ route('ordenes_trabajo.edit', $ordenTrabajo->id) }}" class="btn btn-warning btn-sm">
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
                          <form action="{{ route('ordenes_trabajo.cambiar_estado', $ordenTrabajo->id) }}" method="POST" style="display: inline;">
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

            <!-- Gestión de Tareas -->
            <div class="card card-outline card-secondary">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-tasks"></i> Gestión de Tareas</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#asignarTareaModal">
                    <i class="fas fa-plus"></i> Asignar Tarea
                  </button>
                </div>
              </div>
              <div class="card-body">
                @if($ordenTrabajo->tareas->count() > 0)
                  <div class="table-responsive">
                    <table class="table table-hover table-sm">
                      <thead>
                        <tr>
                          <th>Tarea</th>
                          <th>Tipo</th>
                          <th>Estado</th>
                          <th>Asignado a</th>
                          <th>Grupo</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($ordenTrabajo->tareas as $tarea)
                          <tr>
                            <td>
                              <strong>{{ $tarea->nombre }}</strong>
                              <br><small class="text-muted">{{ $tarea->titulo }}</small>
                            </td>
                            <td>
                              <span class="badge badge-info">{{ $tarea->tipo }}</span>
                            </td>
                            <td>
                              <span class="badge badge-{{ $tarea->estado === 'completada' ? 'success' : ($tarea->estado === 'en_proceso' ? 'warning' : 'secondary') }}">
                                {{ $tarea->estado }}
                              </span>
                            </td>
                            <td>
                              @if($tarea->empleado)
                                {{ $tarea->empleado->name }}
                              @else
                                <span class="text-muted">Sin asignar</span>
                              @endif
                            </td>
                            <td>
                              @if($tarea->movil)
                                {{ $tarea->movil->nombre }}
                              @else
                                <span class="text-muted">Sin grupo</span>
                              @endif
                            </td>
                            <td>
                              <div class="btn-group">
                                @if($tarea->estado !== 'completada')
                                  <button type="button" class="btn btn-warning btn-sm cambiar-estado-tarea"
                                          data-tarea-id="{{ $tarea->id }}"
                                          data-estado-actual="{{ $tarea->estado }}">
                                    <i class="fas fa-play"></i>
                                  </button>
                                @endif
                                <form action="{{ route('tareas.destroy', $tarea) }}" method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger btn-sm"
                                          onclick="return confirm('¿Eliminar esta tarea de la orden?')">
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
                @else
                  <div class="text-center py-4">
                    <i class="fas fa-tasks fa-3x text-muted mb-3"></i>
                    <h5>No hay tareas asignadas</h5>
                    <p class="text-muted">Asigna tareas para comenzar a trabajar en esta orden.</p>
                    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#asignarTareaModal">
                      <i class="fas fa-plus"></i> Asignar Primera Tarea
                    </button>
                  </div>
                @endif
              </div>
            </div>
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
                    @if($ordenTrabajo->vehiculo->marca && $ordenTrabajo->vehiculo->modelo)
                      <tr>
                        <td><strong>Marca:</strong></td>
                        <td>{{ $ordenTrabajo->vehiculo->marca }}</td>
                      </tr>
                      <tr>
                        <td><strong>Modelo:</strong></td>
                        <td>{{ $ordenTrabajo->vehiculo->modelo }}</td>
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
                  <a href="{{ route('ordenes_trabajo.edit', $ordenTrabajo->id) }}" class="btn btn-warning btn-block">
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
                  
                  <form action="{{ route('ordenes_trabajo.destroy', $ordenTrabajo->id) }}" method="POST">
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

<!-- Modal para Asignar Tarea -->
<div class="modal fade" id="asignarTareaModal" tabindex="-1" role="dialog" aria-labelledby="asignarTareaModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="asignarTareaModalLabel">
          <i class="fas fa-plus"></i> Asignar Tarea a la Orden
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="asignarTareaForm">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="tarea_plantilla">Seleccionar Tarea Plantilla <span class="text-danger">*</span></label>
            <select class="form-control" id="tarea_plantilla" name="tarea_plantilla" required>
              <option value="">Selecciona una tarea...</option>
              <!-- Las opciones se cargarán vía AJAX -->
            </select>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="empleado_id">Asignar a Técnico</label>
                <select class="form-control" id="empleado_id" name="empleado_id">
                  <option value="">Seleccionar técnico...</option>
                  @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group">
                <label for="movil_id">Asignar a Grupo de Trabajo</label>
                <select class="form-control" id="movil_id" name="movil_id">
                  <option value="">Seleccionar grupo...</option>
                  @foreach(\App\Models\GrupoTrabajo::where('activo', true)->get() as $grupo)
                    <option value="{{ $grupo->id }}">{{ $grupo->nombre }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label for="observaciones_tarea">Observaciones Adicionales</label>
            <textarea class="form-control" id="observaciones_tarea" name="observaciones_tarea" rows="3"
                      placeholder="Observaciones específicas para esta tarea..."></textarea>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            <i class="fas fa-times"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-success" id="btnAsignarTarea">
            <i class="fas fa-plus"></i> Asignar Tarea
          </button>
        </div>
      </form>
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

// Cargar tareas disponibles cuando se abre el modal
$('#asignarTareaModal').on('show.bs.modal', function() {
  cargarTareasDisponibles();
});

// Función para cargar tareas disponibles
function cargarTareasDisponibles() {
  $.ajax({
    url: '{{ route("tareas.disponibles") }}',
    method: 'GET',
    data: {
      orden_trabajo_id: {{ $ordenTrabajo->id }}
    },
    success: function(response) {
      let options = '<option value="">Selecciona una tarea...</option>';
      response.forEach(function(tarea) {
        options += `<option value="${tarea.id}">${tarea.nombre} (${tarea.tipo})</option>`;
      });
      $('#tarea_plantilla').html(options);
    },
    error: function(xhr) {
      console.error('Error al cargar tareas:', xhr.responseText);
      alert('Error al cargar las tareas disponibles');
    }
  });
}

// Asignar tarea al enviar el formulario
$('#asignarTareaForm').on('submit', function(e) {
  e.preventDefault();

  const tareaId = $('#tarea_plantilla').val();
  const empleadoId = $('#empleado_id').val();
  const movilId = $('#movil_id').val();

  if (!tareaId) {
    alert('Debes seleccionar una tarea');
    return;
  }

  // Mostrar loading
  $('#btnAsignarTarea').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Asignando...');

  $.ajax({
    url: `/tareas/${tareaId}/asignar-orden`,
    method: 'POST',
    data: {
      _token: '{{ csrf_token() }}',
      orden_trabajo_id: {{ $ordenTrabajo->id }},
      empleado_id: empleadoId,
      movil_id: movilId,
      observaciones: $('#observaciones_tarea').val()
    },
    success: function(response) {
      if (response.success) {
        $('#asignarTareaModal').modal('hide');
        location.reload(); // Recargar la página para mostrar la nueva tarea
      } else {
        alert('Error: ' + (response.message || 'No se pudo asignar la tarea'));
      }
    },
    error: function(xhr) {
      console.error('Error:', xhr.responseText);
      let message = 'Error al asignar la tarea';
      if (xhr.responseJSON && xhr.responseJSON.error) {
        message = xhr.responseJSON.error;
      }
      alert(message);
    },
    complete: function() {
      $('#btnAsignarTarea').prop('disabled', false).html('<i class="fas fa-plus"></i> Asignar Tarea');
    }
  });
});

// Cambiar estado de tarea
$('.cambiar-estado-tarea').on('click', function() {
  const tareaId = $(this).data('tarea-id');
  const estadoActual = $(this).data('estado-actual');

  // Determinar siguiente estado
  let nuevoEstado = 'en_proceso';
  if (estadoActual === 'pendiente') {
    nuevoEstado = 'en_proceso';
  } else if (estadoActual === 'en_proceso') {
    nuevoEstado = 'completada';
  }

  const confirmacion = confirm(`¿Cambiar el estado de la tarea a "${nuevoEstado}"?`);

  if (confirmacion) {
    $.ajax({
      url: `/tareas/${tareaId}/actualizar-estado`,
      method: 'PATCH',
      data: {
        _token: '{{ csrf_token() }}',
        estado: nuevoEstado,
        completada: nuevoEstado === 'completada'
      },
      success: function(response) {
        if (response.success) {
          location.reload(); // Recargar para mostrar cambios
        } else {
          alert('Error al actualizar el estado');
        }
      },
      error: function(xhr) {
        console.error('Error:', xhr.responseText);
        alert('Error al actualizar el estado de la tarea');
      }
    });
  }
});

// Limpiar formulario cuando se cierra el modal
$('#asignarTareaModal').on('hidden.bs.modal', function() {
  $('#asignarTareaForm')[0].reset();
  $('#tarea_plantilla').html('<option value="">Selecciona una tarea...</option>');
});
</script>
@endpush
