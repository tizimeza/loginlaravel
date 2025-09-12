@extends('layouts.admin')

@section('title', 'Equipo de Trabajo: ' . $grupoTrabajo->nombre)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('grupos_trabajo.index')}}">Equipos de Trabajo</a></li>
<li class="breadcrumb-item active">{{ $grupoTrabajo->nombre }}</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header" style="background-color: {{ $grupoTrabajo->color ? 'var(--' . $grupoTrabajo->color . ')' : 'var(--primary)' }}; color: white;">
        <h3 class="card-title">
          <i class="fas fa-users mr-1"></i>
          Equipo de Trabajo: {{ $grupoTrabajo->nombre }}
        </h3>
        <div class="card-tools">
          <a href="{{ route('grupos_trabajo.edit', $grupoTrabajo->id) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i> Editar
          </a>
          <a href="{{ route('grupos_trabajo.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <div class="card-body">
        <!-- Información General -->
        <div class="row">
          <div class="col-md-6">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h4 class="card-title">
                  <i class="fas fa-info-circle mr-1"></i>
                  Información General
                </h4>
              </div>
              <div class="card-body">
                <dl class="row">
                  <dt class="col-sm-4">Nombre:</dt>
                  <dd class="col-sm-8">{{ $grupoTrabajo->nombre }}</dd>

                  <dt class="col-sm-4">Descripción:</dt>
                  <dd class="col-sm-8">{{ $grupoTrabajo->descripcion ?: 'Sin descripción' }}</dd>

                  <dt class="col-sm-4">Líder:</dt>
                  <dd class="col-sm-8">
                    @if($grupoTrabajo->lider)
                      {{ $grupoTrabajo->lider->name }}
                    @else
                      <span class="text-muted">Sin líder asignado</span>
                    @endif
                  </dd>

                  <dt class="col-sm-4">Especialidad:</dt>
                  <dd class="col-sm-8">
                    <span class="badge badge-info">{{ $grupoTrabajo->especialidad ? \App\Models\GrupoTrabajo::ESPECIALIDADES[$grupoTrabajo->especialidad] : 'General' }}</span>
                  </dd>

                  <dt class="col-sm-4">Zona:</dt>
                  <dd class="col-sm-8">
                    @if($grupoTrabajo->zona_trabajo)
                      {{ \App\Models\GrupoTrabajo::ZONAS_TRABAJO[$grupoTrabajo->zona_trabajo] }}
                    @else
                      <span class="text-muted">Sin zona definida</span>
                    @endif
                  </dd>

                  <dt class="col-sm-4">Estado:</dt>
                  <dd class="col-sm-8">
                    <span class="badge badge-{{ $grupoTrabajo->activo ? 'success' : 'danger' }}">
                      {{ $grupoTrabajo->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                  </dd>

                  <dt class="col-sm-4">Capacidad:</dt>
                  <dd class="col-sm-8">{{ $grupoTrabajo->capacidad_maxima ?: 'No definida' }}</dd>
                </dl>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h4 class="card-title">
                  <i class="fas fa-chart-bar mr-1"></i>
                  Estadísticas
                </h4>
              </div>
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="small-box bg-info">
                      <div class="inner">
                        <h3>{{ $estadisticas['total_miembros'] }}</h3>
                        <p>Miembros</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-users"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="small-box bg-warning">
                      <div class="inner">
                        <h3>{{ $estadisticas['ordenes_activas'] }}</h3>
                        <p>Órdenes Activas</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-clock"></i>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="small-box bg-success">
                      <div class="inner">
                        <h3>{{ $estadisticas['ordenes_completadas'] }}</h3>
                        <p>Completadas</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-check"></i>
                      </div>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="small-box bg-danger">
                      <div class="inner">
                        <h3>{{ $estadisticas['ordenes_fallidas'] }}</h3>
                        <p>Fallidas</p>
                      </div>
                      <div class="icon">
                        <i class="fas fa-times"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Miembros del Grupo -->
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-success">
              <div class="card-header">
                <h4 class="card-title">
                  <i class="fas fa-users mr-1"></i>
                  Miembros del Grupo ({{ $estadisticas['total_miembros'] }})
                </h4>
                <div class="card-tools">
                  <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#agregarMiembroModal">
                    <i class="fas fa-plus"></i> Agregar Miembro
                  </button>
                </div>
              </div>
              <div class="card-body">
                @if($grupoTrabajo->miembros->count() > 0)
                  <div class="row">
                    @foreach($grupoTrabajo->miembros as $miembro)
                      <div class="col-md-3 mb-3">
                        <div class="card h-100">
                          <div class="card-body text-center">
                            <div class="avatar-circle mb-2" style="background-color: var(--{{ $grupoTrabajo->color ?: 'primary' }}); color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                              <i class="fas fa-user fa-2x"></i>
                            </div>
                            <h6 class="card-title mb-1">{{ $miembro->name }}</h6>
                            <p class="card-text small text-muted">{{ $miembro->email }}</p>
                            @if($miembro->id == $grupoTrabajo->lider_id)
                              <span class="badge badge-warning">Líder</span>
                            @endif
                            <div class="mt-2">
                              @if($miembro->id != $grupoTrabajo->lider_id)
                                <form action="{{ route('grupos_trabajo.remover_miembro', [$grupoTrabajo->id, $miembro->id]) }}" method="POST" class="d-inline">
                                  @csrf
                                  @method('DELETE')
                                  <button type="submit" class="btn btn-danger btn-xs"
                                          onclick="return confirm('¿Estás seguro de que deseas remover a {{ $miembro->name }} del grupo?')">
                                    <i class="fas fa-user-minus"></i>
                                  </button>
                                </form>
                              @endif
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                @else
                  <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Este grupo no tiene miembros asignados.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>

        <!-- Órdenes de Trabajo Asignadas -->
        <div class="row">
          <div class="col-12">
            <div class="card card-outline card-warning">
              <div class="card-header">
                <h4 class="card-title">
                  <i class="fas fa-clipboard-list mr-1"></i>
                  Órdenes de Trabajo Asignadas
                </h4>
              </div>
              <div class="card-body">
                @if($grupoTrabajo->ordenesAsignadas->count() > 0)
                  <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                      <thead>
                        <tr>
                          <th>Número</th>
                          <th>Cliente</th>
                          <th>Estado</th>
                          <th>Fecha Asignación</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($grupoTrabajo->ordenesAsignadas as $orden)
                          <tr>
                            <td>{{ $orden->numero_orden }}</td>
                            <td>
                              @if($orden->cliente)
                                {{ $orden->cliente->nombre }}
                              @else
                                <span class="text-muted">Sin cliente</span>
                              @endif
                            </td>
                            <td>
                              <span class="badge badge-{{ $orden->estado == 'nueva' ? 'secondary' : ($orden->estado == 'en_proceso' ? 'warning' : ($orden->estado == 'terminada' ? 'success' : 'danger')) }}">
                                {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                              </span>
                            </td>
                            <td>{{ $orden->fecha_asignacion ? \Carbon\Carbon::parse($orden->fecha_asignacion)->format('d/m/Y') : 'Sin fecha' }}</td>
                            <td>
                              <a href="{{ route('ordenes_trabajo.show', $orden->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-eye"></i>
                              </a>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @else
                  <div class="text-center py-4">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Este grupo no tiene órdenes de trabajo asignadas.</p>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="card-footer">
        <a href="{{ route('grupos_trabajo.edit', $grupoTrabajo->id) }}" class="btn btn-warning">
          <i class="fas fa-edit"></i> Editar Grupo
        </a>
        <a href="{{ route('grupos_trabajo.index') }}" class="btn btn-secondary">
          <i class="fas fa-arrow-left"></i> Volver
        </a>
        <div class="float-right">
          <form action="{{ route('grupos_trabajo.destroy', $grupoTrabajo->id) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                    onclick="return confirm('¿Estás seguro de que deseas eliminar el grupo {{ $grupoTrabajo->nombre }}?')">
              <i class="fas fa-trash"></i> Eliminar Grupo
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal para agregar miembro -->
<div class="modal fade" id="agregarMiembroModal" tabindex="-1" role="dialog" aria-labelledby="agregarMiembroModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="agregarMiembroModalLabel">Agregar Miembro al Grupo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('grupos_trabajo.agregar_miembro', $grupoTrabajo->id) }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="user_id">Seleccionar Usuario</label>
            <select class="form-control" id="user_id" name="user_id" required>
              <option value="">Seleccionar usuario...</option>
              @foreach(\App\Models\User::all() as $usuario)
                @if(!$grupoTrabajo->esMiembro($usuario->id))
                  <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary">Agregar Miembro</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('js')
<script>
$(document).ready(function() {
    // Inicializar tooltips si es necesario
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection
