@extends('layouts.admin')

@section('title', 'Clientes')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Clientes</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-users mr-1"></i>
          Gestión de Clientes
        </h3>
        <div class="card-tools">
          <a href="{{ route('clientes.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo Cliente
          </a>
        </div>
      </div>

      <!-- Filtros -->
      <div class="card-body border-bottom">
        <form method="GET" action="{{ route('clientes.index') }}" class="mb-0">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group mb-2">
                <label for="tipo_cliente">Tipo de Cliente</label>
                <select name="tipo_cliente" id="tipo_cliente" class="form-control form-control-sm">
                  <option value="">Todos los tipos</option>
                  @foreach($tiposCliente as $key => $tipo)
                    <option value="{{ $key }}" {{ request('tipo_cliente') == $key ? 'selected' : '' }}>
                      {{ $tipo }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group mb-2">
                <label for="es_premium">Premium</label>
                <select name="es_premium" id="es_premium" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  <option value="1" {{ request('es_premium') === '1' ? 'selected' : '' }}>Sí</option>
                  <option value="0" {{ request('es_premium') === '0' ? 'selected' : '' }}>No</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group mb-2">
                <label for="activo">Estado</label>
                <select name="activo" id="activo" class="form-control form-control-sm">
                  <option value="">Todos</option>
                  <option value="1" {{ request('activo') === '1' ? 'selected' : '' }}>Activo</option>
                  <option value="0" {{ request('activo') === '0' ? 'selected' : '' }}>Inactivo</option>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group mb-2">
                <label for="search">Buscar</label>
                <input type="text" name="search" id="search" class="form-control form-control-sm"
                       value="{{ request('search') }}" placeholder="Nombre, email, teléfono...">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group mb-2">
                <label>&nbsp;</label>
                <div>
                  <button type="submit" class="btn btn-primary btn-sm">
                    <i class="fas fa-search"></i> Filtrar
                  </button>
                  <a href="{{ route('clientes.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-times"></i> Limpiar
                  </a>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>

      <!-- Tabla de clientes -->
      <div class="card-body table-responsive p-0">
        @if($clientes->count() > 0)
          <table class="table table-hover text-nowrap">
            <thead>
              <tr>
                <th>Nombre</th>
                <th>Contacto</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th>Premium</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($clientes as $cliente)
                <tr>
                  <td>
                    <strong>{{ $cliente->nombre }}</strong>
                    @if($cliente->documento)
                      <br><small class="text-muted">{{ $cliente->documento }}</small>
                    @endif
                  </td>
                  <td>
                    <i class="fas fa-phone text-primary"></i> {{ $cliente->telefono }}
                    @if($cliente->email)
                      <br><i class="fas fa-envelope text-info"></i> {{ $cliente->email }}
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-{{ $cliente->color_tipo }}">
                      {{ $cliente->tipo_cliente_formateado }}
                    </span>
                  </td>
                  <td>
                    @if($cliente->activo)
                      <span class="badge badge-success">Activo</span>
                    @else
                      <span class="badge badge-secondary">Inactivo</span>
                    @endif
                  </td>
                  <td>
                    @if($cliente->es_premium)
                      <span class="badge badge-warning">
                        <i class="fas fa-star"></i> Premium
                      </span>
                    @else
                      <span class="text-muted">No</span>
                    @endif
                  </td>
                  <td>
                    <div class="btn-group">
                      <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-info btn-sm" title="Ver">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      @if($cliente->activo)
                        <form action="{{ route('clientes.toggle_activo', $cliente) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-secondary btn-sm" title="Desactivar"
                                  onclick="return confirm('¿Está seguro de desactivar este cliente?')">
                            <i class="fas fa-ban"></i>
                          </button>
                        </form>
                      @else
                        <form action="{{ route('clientes.toggle_activo', $cliente) }}" method="POST" class="d-inline">
                          @csrf
                          @method('PATCH')
                          <button type="submit" class="btn btn-success btn-sm" title="Activar"
                                  onclick="return confirm('¿Está seguro de activar este cliente?')">
                            <i class="fas fa-check"></i>
                          </button>
                        </form>
                      @endif
                      <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" title="Eliminar"
                                onclick="return confirm('¿Está seguro de eliminar este cliente? Esta acción no se puede deshacer.')">
                          <i class="fas fa-trash"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @else
          <div class="text-center py-5">
            <i class="fas fa-users fa-3x text-muted mb-3"></i>
            <h4>No hay clientes registrados</h4>
            <p class="text-muted">Comienza creando tu primer cliente.</p>
            <a href="{{ route('clientes.create') }}" class="btn btn-primary">
              <i class="fas fa-plus"></i> Crear Primer Cliente
            </a>
          </div>
        @endif
      </div>

      <!-- Paginación -->
      @if($clientes->hasPages())
        <div class="card-footer">
          {{ $clientes->appends(request()->query())->links() }}
        </div>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
  // Auto-submit form when filters change
  $('#tipo_cliente, #es_premium, #activo').on('change', function() {
    $(this).closest('form').submit();
  });
});
</script>
@endpush
