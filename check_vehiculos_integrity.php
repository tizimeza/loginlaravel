<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Vehiculo;
use App\Models\Modelo;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE INTEGRIDAD DE VEHÍCULOS ===\n\n";

// Verificar vehiculos con problemas
$vehiculos = Vehiculo::with('modelo.marca')->get();
$problemas = [];

foreach ($vehiculos as $vehiculo) {
    $problema = [];

    // Verificar si modelo_id existe
    if ($vehiculo->modelo_id && !$vehiculo->modelo) {
        $problema[] = "modelo_id ({$vehiculo->modelo_id}) no existe en tabla modelos";
    }

    // Verificar si modelo es string en lugar de objeto
    if (is_string($vehiculo->modelo)) {
        $problema[] = "campo 'modelo' es string en lugar de objeto: " . $vehiculo->modelo;
    }

    if (!empty($problema)) {
        $problemas[] = [
            'vehiculo_id' => $vehiculo->id,
            'patente' => $vehiculo->patente,
            'modelo_id' => $vehiculo->modelo_id,
            'problemas' => $problema
        ];
    }
}

if (!empty($problemas)) {
    echo "❌ VEHÍCULOS CON PROBLEMAS ENCONTRADOS:\n";
    foreach ($problemas as $problema) {
        echo "  ID: {$problema['vehiculo_id']}, Patente: {$problema['patente']}, Modelo ID: {$problema['modelo_id']}\n";
        foreach ($problema['problemas'] as $desc) {
            echo "    - $desc\n";
        }
        echo "\n";
    }
} else {
    echo "✅ No se encontraron problemas de integridad en los vehículos.\n\n";
}

// Mostrar estadísticas
echo "📊 ESTADÍSTICAS:\n";
echo "   Total de vehículos: " . $vehiculos->count() . "\n";

$conModelo = $vehiculos->filter(function($v) { return $v->modelo; })->count();
echo "   Vehículos con modelo válido: $conModelo\n";

$sinModelo = $vehiculos->filter(function($v) { return !$v->modelo; })->count();
echo "   Vehículos sin modelo: $sinModelo\n";

$conMarca = $vehiculos->filter(function($v) { return $v->modelo && $v->modelo->marca; })->count();
echo "   Vehículos con marca válida: $conMarca\n";

echo "\n=== PRUEBA DE CARGA DE RELACIONES ===\n";

try {
    // Probar la consulta que se usa en el controlador
    $vehiculosTest = Vehiculo::with('modelo.marca')->get();
    echo "✅ Consulta Vehiculo::with('modelo.marca')->get() funciona correctamente\n";

    foreach ($vehiculosTest->take(3) as $vehiculo) {
        echo "  Vehículo {$vehiculo->patente}: ";
        if ($vehiculo->modelo && $vehiculo->modelo->marca) {
            echo "{$vehiculo->modelo->marca->nombre} {$vehiculo->modelo->nombre}\n";
        } else {
            echo "Sin modelo válido\n";
        }
    }

} catch (Exception $e) {
    echo "❌ ERROR en la consulta: " . $e->getMessage() . "\n";
}
