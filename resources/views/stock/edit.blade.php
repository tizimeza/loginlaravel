@extends('layouts.admin')

@section('title', 'Editar Producto')

@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('home')}}">Inicio</a></li>
<li class="breadcrumb-item"><a href="{{route('stock.index')}}">Stock</a></li>
<li class="breadcrumb-item"><a href="{{route('stock.show', $stock)}}">{{ $stock->codigo }}</a></li>
<li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          <i class="fas fa-edit mr-1"></i>
          Editar Producto: {{ $stock->nombre }}
        </h3>
        <div class="card-tools">
          <a href="{{ route('stock.show', $stock) }}" class="btn btn-info btn-sm">
            <i class="fas fa-eye"></i> Ver
          </a>
          <a href="{{ route('stock.index') }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Volver
          </a>
        </div>
      </div>

      <form action="{{ route('stock.update', $stock) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
          @if ($errors->any())
            <div class="alert alert-danger">
              <h5><i class="icon fas fa-ban"></i> Error!</h5>
              <ul class="mb-0">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="codigo">Código <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('codigo') is-invalid @enderror" 
                       id="codigo" name="codigo" value="{{ old('codigo', $stock->codigo) }}" required>
                @error('codigo')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="nombre">Nombre <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                       id="nombre" name="nombre" value="{{ old('nombre', $stock->nombre) }}" required>
                @error('nombre')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>

              <div class="form-group">
                <label for="categoria">Categoría <span class="text-danger">*</span></label>
                <select class="form-control @error('categoria') is-invalid @enderror" 
                        id="categoria" name="categoria" required>
                  <option value="">Seleccionar categoría</option>
                  @foreach($categorias as $key => $categoria)
                    <option value="{{ $key }}" {{ old('categoria', $stock->categoria) == $key ? 'selected' : '' }}>
                      {{ $categoria }}
                    </option>
                  @endforeach
                </select>
                @error('categoria')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>

            <div class="col-md-6">
              <div class="row">
                <div class="col-4">
                  <div class="form-group">
                    <label for="cantidad_actual">Stock Actual <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('cantidad_actual') is-invalid @enderror" 
                           id="cantidad_actual" name="cantidad_actual" 
                           value="{{ old('cantidad_actual', $stock->cantidad_actual) }}" min="0" required>
                    @error('cantidad_actual')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="cantidad_minima">Stock Mínimo <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('cantidad_minima') is-invalid @enderror" 
                           id="cantidad_minima" name="cantidad_minima" 
                           value="{{ old('cantidad_minima', $stock->cantidad_minima) }}" min="1" required>
                    @error('cantidad_minima')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
                <div class="col-4">
                  <div class="form-group">
                    <label for="cantidad_maxima">Stock Máximo</label>
                    <input type="number" class="form-control @error('cantidad_maxima') is-invalid @enderror" 
                           id="cantidad_maxima" name="cantidad_maxima" 
                           value="{{ old('cantidad_maxima', $stock->cantidad_maxima) }}" min="1">
                    @error('cantidad_maxima')
                      <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label for="precio_compra">Precio Compra <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input type="number" step="0.01" class="form-control @error('precio_compra') is-invalid @enderror" 
                             id="precio_compra" name="precio_compra" 
                             value="{{ old('precio_compra', $stock->precio_compra) }}" min="0" required>
                      @error('precio_compra')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label for="precio_venta">Precio Venta <span class="text-danger">*</span></label>
                    <div class="input-group">
                      <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                      </div>
                      <input type="number" step="0.01" class="form-control @error('precio_venta') is-invalid @enderror" 
                             id="precio_venta" name="precio_venta" 
                             value="{{ old('precio_venta', $stock->precio_venta) }}" min="0" required>
                      @error('precio_venta')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-8">
              <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                          id="descripcion" name="descripcion" rows="3">{{ old('descripcion', $stock->descripcion) }}</textarea>
                @error('descripcion')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
            <div class="col-md-4">
              @if($stock->imagen)
                <div class="mb-3">
                  <label>Imagen Actual:</label><br>
                  <img src="{{ asset('storage/' . $stock->imagen) }}" 
                       alt="Imagen actual" class="img-thumbnail" style="max-width: 150px;">
                </div>
              @endif
              
              <div class="form-group">
                <label for="imagen">{{ $stock->imagen ? 'Cambiar Imagen' : 'Imagen del Producto' }}</label>
                <input type="file" class="form-control-file @error('imagen') is-invalid @enderror" 
                       id="imagen" name="imagen" accept="image/*">
                @error('imagen')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              
              <div class="form-check">
                <input type="checkbox" class="form-check-input" id="activo" name="activo" value="1" 
                       {{ old('activo', $stock->activo) ? 'checked' : '' }}>
                <label class="form-check-label" for="activo">
                  Producto Activo
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="card-footer">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Actualizar Producto
          </button>
          <a href="{{ route('stock.show', $stock) }}" class="btn btn-info">
            <i class="fas fa-eye"></i> Ver Producto
          </a>
          <a href="{{ route('stock.index') }}" class="btn btn-secondary">
            <i class="fas fa-times"></i> Cancelar
          </a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
