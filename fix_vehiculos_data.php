<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Vehiculo;
use App\Models\Modelo;
use Illuminate\Support\Facades\DB;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== CORRECCIÓN DE DATOS DE VEHÍCULOS ===\n\n";

// 1. Obtener IDs válidos de modelos
$modelosValidos = Modelo::pluck('id')->toArray();
echo "📋 Modelos válidos encontrados: " . count($modelosValidos) . "\n";

// 2. Corregir vehiculos con modelo_id inválido
$vehiculosProblema = Vehiculo::whereNotNull('modelo_id')
                             ->whereNotIn('modelo_id', $modelosValidos)
                             ->get();

if ($vehiculosProblema->count() > 0) {
    echo "\n🔧 Corrigiendo vehículos con modelo_id inválido:\n";
    foreach ($vehiculosProblema as $vehiculo) {
        echo "  - Vehículo ID {$vehiculo->id} ({$vehiculo->patente}): modelo_id {$vehiculo->modelo_id} -> NULL\n";

        // Actualizar el vehículo para quitar el modelo_id inválido
        $vehiculo->update([
            'modelo_id' => null,
            'modelo' => 'Sin asignar'
        ]);
    }
    echo "✅ Vehículos corregidos: {$vehiculosProblema->count()}\n";
} else {
    echo "\n✅ No hay vehículos con modelo_id inválido.\n";
}

// 3. Corregir vehiculos donde modelo es string en lugar de null
$vehiculosStringModelo = Vehiculo::where(function($query) {
    $query->whereNotNull('modelo')
          ->orWhere('modelo', '!=', '');
})->get();

if ($vehiculosStringModelo->count() > 0) {
    echo "\n🔧 Corrigiendo vehículos con campo 'modelo' problemático:\n";
    foreach ($vehiculosStringModelo as $vehiculo) {
        if ($vehiculo->modelo_id && in_array($vehiculo->modelo_id, $modelosValidos)) {
            // Si tiene modelo_id válido, dejar que la relación funcione
            echo "  - Vehículo ID {$vehiculo->id} ({$vehiculo->patente}): limpiando campo modelo (tiene modelo_id válido)\n";
            DB::table('vehiculos')->where('id', $vehiculo->id)->update(['modelo' => null]);
        } elseif ($vehiculo->modelo === '' || $vehiculo->modelo === 'Sin especificar') {
            // Si el campo modelo está vacío o tiene el valor por defecto, limpiarlo
            echo "  - Vehículo ID {$vehiculo->id} ({$vehiculo->patente}): limpiando campo modelo vacío\n";
            DB::table('vehiculos')->where('id', $vehiculo->id)->update(['modelo' => null]);
        } else {
            // Si no tiene modelo_id válido, asignar valor por defecto
            echo "  - Vehículo ID {$vehiculo->id} ({$vehiculo->patente}): asignando 'Sin asignar'\n";
            DB::table('vehiculos')->where('id', $vehiculo->id)->update(['modelo' => 'Sin asignar']);
        }
    }
    echo "✅ Vehículos corregidos: {$vehiculosStringModelo->count()}\n";
} else {
    echo "\n✅ No hay vehículos con campo 'modelo' problemático.\n";
}

// 4. Verificación final
echo "\n=== VERIFICACIÓN FINAL ===\n";

$vehiculos = Vehiculo::with('modelo.marca')->get();
$conProblemas = 0;

foreach ($vehiculos as $vehiculo) {
    $problemas = [];

    // Verificar modelo_id inválido
    if ($vehiculo->modelo_id && !in_array($vehiculo->modelo_id, $modelosValidos)) {
        $problemas[] = "modelo_id inválido ({$vehiculo->modelo_id})";
    }

    // Verificar si modelo es string cuando debería ser null
    if (is_string($vehiculo->modelo) && $vehiculo->modelo !== 'Sin asignar') {
        $problemas[] = "modelo es string inesperado: '" . $vehiculo->modelo . "'";
    }

    // Verificar strings vacíos
    if ($vehiculo->modelo === '') {
        $problemas[] = "modelo es string vacío";
    }

    if (!empty($problemas)) {
        echo "❌ Vehículo ID {$vehiculo->id} ({$vehiculo->patente}): " . implode(', ', $problemas) . "\n";
        $conProblemas++;
    }
}

if ($conProblemas === 0) {
    echo "✅ ¡Todos los vehículos están correctos!\n\n";

    // Mostrar algunos ejemplos
    echo "📋 Ejemplos de vehículos corregidos:\n";
    foreach ($vehiculos->take(3) as $vehiculo) {
        echo "  - {$vehiculo->patente}: ";
        if ($vehiculo->modelo && $vehiculo->modelo->marca) {
            echo "{$vehiculo->modelo->marca->nombre} {$vehiculo->modelo->nombre}\n";
        } else {
            echo "Sin modelo asignado\n";
        }
    }
} else {
    echo "\n❌ Aún hay {$conProblemas} vehículos con problemas.\n";
}

echo "\n🎉 Proceso de corrección completado.\n";
