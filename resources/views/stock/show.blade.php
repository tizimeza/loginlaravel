@extends('layouts.admin')

@section('title', 'Producto: ' . $stock->nombre)

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('stock.index')}}">Stock</a></li>
<li class="breadcrumb-item active">{{ $stock->codigo }}</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-box mr-1"></i>
          Producto: {{ $stock->nombre }}
        </h3>
        <div class="card-tools">
          <a href="{{ route('stock.edit', $stock) }}" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i> Editar
          </a>
          <a href="{{ route('stock.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <div class="card-body">
        <div class="row">
          <div class="col-md-8">
            <table class="table table-sm">
              <tr>
                <td><strong>Código:</strong></td>
                <td>{{ $stock->codigo }}</td>
              </tr>
              <tr>
                <td><strong>Nombre:</strong></td>
                <td>{{ $stock->nombre }}</td>
              </tr>
              <tr>
                <td><strong>Categoría:</strong></td>
                <td><span class="badge badge-info">{{ $stock->categoria_formateada }}</span></td>
              </tr>
              <tr>
                <td><strong>Estado del Stock:</strong></td>
                <td><span class="badge badge-{{ $stock->color_stock }}">{{ $stock->estado_stock }}</span></td>
              </tr>
              <tr>
                <td><strong>Stock Actual:</strong></td>
                <td><strong class="text-{{ $stock->color_stock }}">{{ $stock->cantidad_actual }}</strong></td>
              </tr>
              <tr>
                <td><strong>Stock Mínimo:</strong></td>
                <td>{{ $stock->cantidad_minima }}</td>
              </tr>
              @if($stock->cantidad_maxima)
              <tr>
                <td><strong>Stock Máximo:</strong></td>
                <td>{{ $stock->cantidad_maxima }}</td>
              </tr>
              @endif
              <tr>
                <td><strong>Precio de Compra:</strong></td>
                <td>${{ number_format($stock->precio_compra, 2) }}</td>
              </tr>
              <tr>
                <td><strong>Precio de Venta:</strong></td>
                <td>${{ number_format($stock->precio_venta, 2) }}</td>
              </tr>
              @if($stock->ubicacion)
              <tr>
                <td><strong>Ubicación:</strong></td>
                <td>{{ $stock->ubicacion }}</td>
              </tr>
              @endif
              @if($stock->proveedor)
              <tr>
                <td><strong>Proveedor:</strong></td>
                <td>{{ $stock->proveedor }}</td>
              </tr>
              @endif
            </table>
          </div>
          <div class="col-md-4">
            @if($stock->imagen)
              <img src="{{ asset('storage/' . $stock->imagen) }}" 
                   alt="Imagen del producto" 
                   class="img-fluid rounded">
            @else
              <div class="text-center p-4 bg-light rounded">
                <i class="fas fa-box fa-4x text-muted"></i>
                <p class="text-muted mt-2">Sin imagen</p>
              </div>
            @endif
          </div>
        </div>

        @if($stock->descripcion)
        <div class="row mt-3">
          <div class="col-12">
            <strong>Descripción:</strong>
            <p>{{ $stock->descripcion }}</p>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
