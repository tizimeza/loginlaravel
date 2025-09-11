@extends('layouts.admin')

@section('title', 'Gestión de Stock')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item active">Stock de Materiales</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-boxes mr-1"></i>
          Stock de Materiales
        </h3>
        <div class="card-tools">
          <a href="{{ route('stock.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo Producto
          </a>
          <a href="{{ route('stock.stock_bajo') }}" class="btn btn-warning btn-sm">
            <i class="fas fa-exclamation-triangle"></i> Stock Bajo
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
            <form method="GET" action="{{ route('stock.index') }}" class="form-inline">
              <div class="form-group mr-2">
                <input type="text" class="form-control form-control-sm" name="search" 
                       placeholder="Buscar por código, nombre, marca..." value="{{ request('search') }}">
              </div>
              
              <div class="form-group mr-2">
                <select name="categoria" class="form-control form-control-sm">
                  <option value="">Todas las categorías</option>
                  @foreach($categorias as $key => $categoria)
                    <option value="{{ $key }}" {{ request('categoria') == $key ? 'selected' : '' }}>
                      {{ $categoria }}
                    </option>
                  @endforeach
                </select>
              </div>

              <div class="form-group mr-2">
                <select name="estado_stock" class="form-control form-control-sm">
                  <option value="">Todos los estados</option>
                  <option value="sin_stock" {{ request('estado_stock') == 'sin_stock' ? 'selected' : '' }}>Sin Stock</option>
                  <option value="stock_bajo" {{ request('estado_stock') == 'stock_bajo' ? 'selected' : '' }}>Stock Bajo</option>
                  <option value="stock_normal" {{ request('estado_stock') == 'stock_normal' ? 'selected' : '' }}>Stock Normal</option>
                </select>
              </div>

              <button type="submit" class="btn btn-info btn-sm mr-2">
                <i class="fas fa-search"></i> Filtrar
              </button>
              
              <a href="{{ route('stock.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-times"></i> Limpiar
              </a>
            </form>
          </div>
        </div>

        @if($productos->count() > 0)
          <div class="table-responsive">
            <table class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Producto</th>
                  <th>Categoría</th>
                  <th>Stock</th>
                  <th>Estado</th>
                  <th>Precio Venta</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($productos as $producto)
                <tr>
                  <td><strong>{{ $producto->codigo }}</strong></td>
                  <td>
                    <strong>{{ $producto->nombre }}</strong>
                    @if($producto->marca)
                      <br><small class="text-muted">{{ $producto->marca }} {{ $producto->modelo }}</small>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-info">{{ $producto->categoria_formateada }}</span>
                  </td>
                  <td>
                    <strong>{{ $producto->cantidad_actual }}</strong>
                    <small class="text-muted">/ min: {{ $producto->cantidad_minima }}</small>
                  </td>
                  <td>
                    <span class="badge badge-{{ $producto->color_stock }}">
                      {{ $producto->estado_stock }}
                    </span>
                  </td>
                  <td>${{ number_format($producto->precio_venta, 2) }}</td>
                  <td>
                    <div class="btn-group">
                      <a href="{{ route('stock.show', $producto) }}" class="btn btn-info btn-sm">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('stock.edit', $producto) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i>
                      </a>
                    </div>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <!-- Paginación -->
          <div class="d-flex justify-content-center mt-3">
            {{ $productos->appends(request()->query())->links() }}
          </div>
        @else
          <div class="text-center py-4">
            <i class="fas fa-boxes fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No hay productos en stock</h4>
            <p class="text-muted">¡Agrega tu primer producto haciendo clic en "Nuevo Producto"!</p>
            <a href="{{ route('stock.create') }}" class="btn btn-primary">
              <i class="fas fa-plus"></i> Agregar Primer Producto
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
        <h3>{{ $estadisticas['total_productos'] }}</h3>
        <p>Total Productos</p>
      </div>
      <div class="icon">
        <i class="fas fa-boxes"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $estadisticas['productos_stock_bajo'] }}</h3>
        <p>Stock Bajo</p>
      </div>
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $estadisticas['productos_sin_stock'] }}</h3>
        <p>Sin Stock</p>
      </div>
      <div class="icon">
        <i class="fas fa-times-circle"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>${{ number_format($estadisticas['valor_total_inventario'], 0) }}</h3>
        <p>Valor Inventario</p>
      </div>
      <div class="icon">
        <i class="fas fa-dollar-sign"></i>
      </div>
    </div>
  </div>
</div>
@endsection
