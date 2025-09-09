
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalles del Vehículo</title>
    <style>
        body { font-family: sans-serif; }
    </style>
</head>
<body>
    <h1>Detalles del Vehículo</h1>

    {{-- 
      Usamos la variable $vehiculo que pasamos desde el controlador.
      La sintaxis de doble llave {{ }} de Blade escapa los datos para
      prevenir ataques XSS.
    --}}
    
    <p><strong>Patente:</strong> {{ $vehiculo->patente }}</p>
    <p><strong>Color:</strong> {{ $vehiculo->color }}</p>
    <p><strong>Año:</strong> {{ $vehiculo->anio }}</p>
    
    {{-- 
      Accedemos a las relaciones de la misma forma.
      Verificamos que existan para evitar errores.
    --}}
    @if ($vehiculo->modelo)
        <p><strong>Modelo:</strong> {{ $vehiculo->modelo->nombre }}</p>
        
        @if ($vehiculo->modelo->marca)
            <p><strong>Marca:</strong> {{ $vehiculo->modelo->marca->nombre }}</p>
        @endif
    @endif

    <a href="/vehiculos">Volver al listado</a>
</body>
</html>