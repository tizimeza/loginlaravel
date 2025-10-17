@extends('layouts.admin')

@section('title', 'Stock Bajo - Productos')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('stock.index')}}">Stock</a></li>
<li class="breadcrumb-item active">Stock Bajo</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card card-warning">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-exclamation-triangle mr-1"></i>
          Productos con Stock Bajo
        </h3>
        <div class="card-tools">
          <a href="{{ route('stock.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver al Stock
          </a>
          <a href="{{ route('stock.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Nuevo Producto
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

        @if($productos->count() > 0)
          <div class="callout callout-warning">
            <h5><i class="icon fas fa-exclamation-triangle"></i> Atención!</h5>
            <p>Estos productos tienen un nivel de stock igual o inferior al mínimo establecido. Se recomienda realizar pedidos de reposición.</p>
          </div>

          <div class="table-responsive">
            <table id="stockBajoTable" class="table table-bordered table-striped table-hover">
              <thead>
                <tr>
                  <th>Código</th>
                  <th>Producto</th>
                  <th>Categoría</th>
                  <th>Stock Actual</th>
                  <th>Mínimo</th>
                  <th>Máximo</th>
                  <th>Estado</th>
                  <th>Ubicación</th>
                  <th>Proveedor</th>
                  <th>Acciones</th>
                </tr>
              </thead>
              <tbody>
                @foreach($productos as $producto)
                <tr class="{{ $producto->cantidad_actual == 0 ? 'table-danger' : '' }}">
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
                  <td class="text-center">
                    <strong class="text-{{ $producto->cantidad_actual == 0 ? 'danger' : 'warning' }}">
                      {{ $producto->cantidad_actual }}
                    </strong>
                  </td>
                  <td class="text-center">
                    <span class="text-muted">{{ $producto->cantidad_minima }}</span>
                  </td>
                  <td class="text-center">
                    <span class="text-muted">{{ $producto->cantidad_maxima }}</span>
                  </td>
                  <td class="text-center">
                    <span class="badge badge-{{ $producto->color_stock }}">
                      {{ $producto->estado_stock }}
                    </span>
                  </td>
                  <td>
                    <small>{{ $producto->ubicacion ?? 'No especificada' }}</small>
                  </td>
                  <td>
                    <small>{{ $producto->proveedor ?? 'No especificado' }}</small>
                  </td>
                  <td>
                    <div class="btn-group">
                      <a href="{{ route('stock.show', $producto) }}" class="btn btn-info btn-sm" title="Ver detalles">
                        <i class="fas fa-eye"></i>
                      </a>
                      <a href="{{ route('stock.edit', $producto) }}" class="btn btn-warning btn-sm" title="Editar">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-success btn-sm"
                              data-toggle="modal"
                              data-target="#modalAjustarStock{{ $producto->id }}"
                              title="Ajustar stock">
                        <i class="fas fa-plus-circle"></i>
                      </button>
                    </div>
                  </td>
                </tr>

                <!-- Modal para ajustar stock -->
                <div class="modal fade" id="modalAjustarStock{{ $producto->id }}" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header bg-success">
                        <h5 class="modal-title">Ajustar Stock - {{ $producto->nombre }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form method="POST" action="{{ route('stock.ajustar', $producto) }}">
                        @csrf
                        @method('PATCH')
                        <div class="modal-body">
                          <div class="form-group">
                            <label>Tipo de Ajuste</label>
                            <select name="tipo_ajuste" class="form-control" required>
                              <option value="agregar">Agregar al Stock</option>
                              <option value="reducir">Reducir del Stock</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" min="1" required>
                            <small class="text-muted">Stock actual: {{ $producto->cantidad_actual }}</small>
                          </div>
                          <div class="form-group">
                            <label>Motivo</label>
                            <input type="text" name="motivo" class="form-control"
                                   placeholder="Ej: Reposición de inventario">
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                          <button type="submit" class="btn btn-success">Ajustar Stock</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th>Código</th>
                  <th>Producto</th>
                  <th>Categoría</th>
                  <th>Stock Actual</th>
                  <th>Mínimo</th>
                  <th>Máximo</th>
                  <th>Estado</th>
                  <th>Ubicación</th>
                  <th>Proveedor</th>
                  <th>Acciones</th>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="row mt-3">
            <div class="col-md-12">
              <div class="callout callout-info">
                <h5><i class="icon fas fa-info-circle"></i> Sugerencia de Pedidos</h5>
                <p>Se recomienda realizar pedidos para reponer los productos según las siguientes cantidades:</p>
                <ul>
                  @foreach($productos as $producto)
                    @php
                      $cantidad_sugerida = $producto->cantidad_maxima - $producto->cantidad_actual;
                    @endphp
                    @if($cantidad_sugerida > 0)
                      <li>
                        <strong>{{ $producto->nombre }}</strong> ({{ $producto->codigo }}):
                        <span class="badge badge-primary">{{ $cantidad_sugerida }} unidades</span>
                        @if($producto->proveedor)
                          - Proveedor: {{ $producto->proveedor }}
                        @endif
                      </li>
                    @endif
                  @endforeach
                </ul>
              </div>
            </div>
          </div>

        @else
          <div class="text-center py-4">
            <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
            <h4 class="text-success">¡Excelente!</h4>
            <p class="text-muted">No hay productos con stock bajo en este momento.</p>
            <a href="{{ route('stock.index') }}" class="btn btn-primary">
              <i class="fas fa-boxes"></i> Ver Todo el Stock
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Estadísticas de stock bajo -->
@if($productos->count() > 0)
<div class="row mt-3">
  <div class="col-lg-3 col-6">
    <div class="small-box bg-warning">
      <div class="inner">
        <h3>{{ $productos->count() }}</h3>
        <p>Productos Afectados</p>
      </div>
      <div class="icon">
        <i class="fas fa-exclamation-triangle"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-danger">
      <div class="inner">
        <h3>{{ $productos->where('cantidad_actual', 0)->count() }}</h3>
        <p>Sin Stock</p>
      </div>
      <div class="icon">
        <i class="fas fa-times-circle"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-info">
      <div class="inner">
        <h3>
          @php
            $total_reponer = 0;
            foreach($productos as $p) {
              $total_reponer += ($p->cantidad_maxima - $p->cantidad_actual);
            }
          @endphp
          {{ $total_reponer }}
        </h3>
        <p>Unidades a Reponer</p>
      </div>
      <div class="icon">
        <i class="fas fa-boxes"></i>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-6">
    <div class="small-box bg-success">
      <div class="inner">
        <h3>
          @php
            $valor_reposicion = 0;
            foreach($productos as $p) {
              $cantidad = $p->cantidad_maxima - $p->cantidad_actual;
              $valor_reposicion += ($cantidad * $p->precio_compra);
            }
          @endphp
          ${{ number_format($valor_reposicion, 0) }}
        </h3>
        <p>Inversión Estimada</p>
      </div>
      <div class="icon">
        <i class="fas fa-dollar-sign"></i>
      </div>
    </div>
  </div>
</div>
@endif
@endsection

@section('scripts')
<script>
$(document).ready(function() {
  $('#stockBajoTable').DataTable({
    "language": {
      "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
    },
    "responsive": true,
    "autoWidth": false,
    "order": [[3, 'asc']], // Ordenar por stock actual ascendente
    "pageLength": 25
  });
});
</script>
@endsection
