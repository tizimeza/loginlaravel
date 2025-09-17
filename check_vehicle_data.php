<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Vehiculo;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE DATOS PARA VEHÍCULOS ===\n\n";

// Verificar marcas
$marcas = Marca::all();
echo "📋 MARCAS ENCONTRADAS: " . $marcas->count() . "\n";
if ($marcas->isEmpty()) {
    echo "❌ No hay marcas registradas. Necesitas crear marcas primero.\n";
} else {
    foreach ($marcas as $marca) {
        echo "  - ID: " . $marca->id . ", Nombre: " . $marca->nombre . "\n";
    }
}
echo "\n";

// Verificar modelos
$modelos = Modelo::with('marca')->get();
echo "🚗 MODELOS ENCONTRADOS: " . $modelos->count() . "\n";
if ($modelos->isEmpty()) {
    echo "❌ No hay modelos registrados. Necesitas crear modelos primero.\n";
} else {
    foreach ($modelos as $modelo) {
        $marcaNombre = $modelo->marca ? $modelo->marca->nombre : 'Sin marca';
        echo "  - ID: " . $modelo->id . ", Nombre: " . $modelo->nombre . ", Marca: " . $marcaNombre . "\n";
    }
}
echo "\n";

// Verificar vehiculos existentes
$vehiculos = Vehiculo::with('modelo.marca')->get();
echo "🚛 VEHÍCULOS ENCONTRADOS: " . $vehiculos->count() . "\n";
if ($vehiculos->isNotEmpty()) {
    foreach ($vehiculos as $vehiculo) {
        $modeloNombre = $vehiculo->modelo ? $vehiculo->modelo->nombre : 'Sin modelo';
        echo "  - Patente: " . $vehiculo->patente . ", Modelo: " . $modeloNombre . ", Estado: " . $vehiculo->estado . "\n";
    }
}
echo "\n";

// Si no hay modelos, crear algunos de ejemplo
if ($modelos->isEmpty()) {
    echo "🔧 CREANDO MARCAS Y MODELOS DE EJEMPLO...\n";

    // Crear marcas
    $ford = Marca::create(['nombre' => 'Ford']);
    $renault = Marca::create(['nombre' => 'Renault']);
    $peugeot = Marca::create(['nombre' => 'Peugeot']);

    // Crear modelos
    Modelo::create([
        'nombre' => 'Transit',
        'marca_id' => $ford->id
    ]);

    Modelo::create([
        'nombre' => 'Kangoo',
        'marca_id' => $renault->id
    ]);

    Modelo::create([
        'nombre' => 'Partner',
        'marca_id' => $peugeot->id
    ]);

    echo "✅ Marcas y modelos de ejemplo creados exitosamente!\n";
    echo "Ahora puedes crear vehículos.\n";
}
