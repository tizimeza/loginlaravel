@extends('layouts.admin')

@section('title', 'Equipos de Trabajo de TecnoServi')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Equipos de Trabajo</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-truck mr-1"></i>
          Equipos de Trabajo de TecnoServi
        </h3>
        <div class="card-tools">
          <a href="{{ route('grupos_trabajo.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo Equipo
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

        @if(session('error'))
          <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Error!</h5>
            {{ session('error') }}
          </div>
        @endif

        <!-- Filtros -->
        <div class="row mb-3">
          <div class="col-md-12">
            <form method="GET" action="{{ route('grupos_trabajo.index') }}" class="form-inline">
              <div class="form-group mr-2">
                <label for="search" class="sr-only">Buscar</label>
                <input type="text" class="form-control form-control-sm" name="search" id="search" 
                       placeholder="Buscar por nombre o descripción..." value="{{ request('search') }}">
              </div>
              
              <div class="form-group mr-2">
                <select name="especialidad" class="form-control form-control-sm">
                  <option value="">Todas las especialidades</option>
                  @foreach($especialidades as $key => $especialidad)
                    <option value="{{ $key }}" {{ request('especialidad') == $key ? 'selected' : '' }}>
                      {{ $especialidad }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mr-2">
                <select name="activo" class="form-control form-control-sm">
                  <option value="">Todos los estados</option>
                  <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activos</option>
                  <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivos</option>
                </select>
              </div>

              <button type="submit" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-search"></i> Filtrar
              </button>
              
              <a href="{{ route('grupos_trabajo.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Limpiar
              </a>
            </form>
          </div>
        </div>

        @if($grupos->count() > 0)
          <div class="row">
            @foreach($grupos as $grupo)
              <div class="col-lg-4 col-md-6">
                <div class="card card-outline card-{{ $grupo->color }}">
                  <div class="card-header">
                    <h3 class="card-title">
                      <i class="fas fa-users text-{{ $grupo->color }}"></i>
                      {{ $grupo->nombre }}
                      @if(!$grupo->activo)
                        <span class="badge badge-secondary ml-1">Inactivo</span>
                      @endif
                    </h3>
                    <div class="card-tools">
                      <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-tool dropdown-toggle" data-toggle="dropdown">
                          <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                          <a href="{{ route('grupos_trabajo.show', $grupo) }}" class="dropdown-item">
                            <i class="fas fa-eye"></i> Ver detalles
                          </a>
                          <a href="{{ route('grupos_trabajo.edit', $grupo) }}" class="dropdown-item">
                            <i class="fas fa-edit"></i> Editar
                          </a>
                          <div class="dropdown-divider"></div>
                          <form action="{{ route('grupos_trabajo.toggle_activo', $grupo) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="dropdown-item">
                              @if($grupo->activo)
                                <i class="fas fa-pause text-warning"></i> Desactivar
                              @else
                                <i class="fas fa-play text-success"></i> Activar
                              @endif
                            </button>
                          </form>
                          <div class="dropdown-divider"></div>
                          <form action="{{ route('grupos_trabajo.destroy', $grupo) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger" 
                                    onclick="return confirm('¿Estás seguro de que deseas eliminar este grupo?')">
                              <i class="fas fa-trash"></i> Eliminar
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <strong>Especialidad:</strong>
                        <span class="badge badge-info">{{ $grupo->especialidad_formateada }}</span>
                      </div>
                    </div>
                    
                    @if($grupo->descripcion)
                      <div class="row mt-2">
                        <div class="col-12">
                          <strong>Descripción:</strong>
                          <p class="text-muted">{{ Str::limit($grupo->descripcion, 80) }}</p>
                        </div>
                      </div>
                    @endif

                    <div class="row mt-2">
                      <div class="col-6">
                        <strong>Líder:</strong><br>
                        @if($grupo->lider)
                          <span class="text-primary">
                            <i class="fas fa-user-tie"></i> {{ $grupo->lider->name }}
                          </span>
                        @else
                          <span class="text-muted">Sin líder</span>
                        @endif
                      </div>
                      <div class="col-6">
                        <strong>Miembros:</strong><br>
                        <span class="text-info">
                          <i class="fas fa-users"></i> {{ $grupo->numero_miembros }} 
                          {{ $grupo->numero_miembros === 1 ? 'miembro' : 'miembros' }}
                        </span>
                      </div>
                    </div>

                    <div class="row mt-2">
                      <div class="col-12">
                        <strong>Órdenes Activas:</strong>
                        <span class="badge badge-{{ $grupo->ordenes_activas > 0 ? 'warning' : 'success' }}">
                          {{ $grupo->ordenes_activas }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="btn-group btn-block">
                      <a href="{{ route('grupos_trabajo.show', $grupo) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i> Ver
                      </a>
                      <a href="{{ route('grupos_trabajo.edit', $grupo) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Editar
                      </a>
                      <form action="{{ route('grupos_trabajo.toggle_activo', $grupo) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-{{ $grupo->activo ? 'secondary' : 'success' }} btn-sm">
                          @if($grupo->activo)
                            <i class="fas fa-pause"></i> Desactivar
                          @else
                            <i class="fas fa-play"></i> Activar
                          @endif
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <!-- Paginación -->
          <div class="d-flex justify-content-center mt-3">
            {{ $grupos->appends(request()->query())->links() }}
          </div>
        @else
          <div class="text-center py-4">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay grupos de trabajo registrados</h4>
            <p class="text-muted">¡Crea tu primer grupo de trabajo haciendo clic en el botón "Nuevo Grupo"!</p>
            <a href="{{ route('grupos_trabajo.create') }}" class="btn btn-primary">
              <i class="fas fa-plus"></i> Crear Primer Grupo
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')
<script>
$(document).ready(function() {
  // Confirmación para cambios de estado
  $('form[action*="toggle_activo"]').on('submit', function(e) {
    const form = $(this);
    const isActive = form.find('button').hasClass('btn-secondary');
    const action = isActive ? 'desactivar' : 'activar';

    if (!confirm(`¿Confirmar ${action} el grupo?`)) {
      e.preventDefault();
    }
  });
});
</script>
@endsection
