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

echo "=== PRUEBA DE INSERCIÓN DE VEHÍCULO ===\n\n";

// Obtener un modelo existente para usar como referencia
$modelo = Modelo::with('marca')->first();
if (!$modelo) {
    echo "❌ ERROR: No hay modelos disponibles en la base de datos.\n";
    exit(1);
}

echo "📋 Usando modelo de referencia:\n";
echo "   ID: " . $modelo->id . "\n";
echo "   Nombre: " . $modelo->nombre . "\n";
echo "   Marca: " . $modelo->marca->nombre . "\n\n";

// Datos de prueba para insertar un vehículo
$data = [
    'patente' => 'AB123RT',
    'color' => 'Rojo',
    'anio' => 2025,
    'modelo_id' => $modelo->id,
    // Los campos con valores por defecto se manejarán automáticamente
    'imagen' => 'vehiculos/1758135050_740247_1_im.jpg'
];

echo "🚗 Intentando crear vehículo con los siguientes datos:\n";
echo "   Patente: " . $data['patente'] . "\n";
echo "   Color: " . $data['color'] . "\n";
echo "   Año: " . $data['anio'] . "\n";
echo "   Modelo ID: " . $data['modelo_id'] . "\n";
echo "   Imagen: " . $data['imagen'] . "\n\n";

try {
    $vehiculo = Vehiculo::create($data);
    echo "✅ ¡Vehículo insertado exitosamente!\n";
    echo "   ID: " . $vehiculo->id . "\n";
    echo "   Patente: " . $vehiculo->patente . "\n";
    echo "   Color: " . $vehiculo->color . "\n";
    echo "   Año: " . $vehiculo->anio . "\n";
    echo "   Estado: " . $vehiculo->estado . "\n";
    echo "   Tipo de vehículo: " . $vehiculo->tipo_vehiculo . "\n";
    echo "   Marca: " . $vehiculo->marca . "\n";
    echo "   Modelo: " . $vehiculo->modelo . "\n";

} catch (Exception $e) {
    echo "❌ ERROR al insertar el vehículo: " . $e->getMessage() . "\n";

    // Información adicional para debug
    echo "\n🔍 INFORMACIÓN DE DEBUG:\n";

    // Verificar si la patente ya existe
    $existePatente = Vehiculo::where('patente', $data['patente'])->exists();
    echo "   - Patente ya existe: " . ($existePatente ? 'SÍ' : 'NO') . "\n";

    // Verificar si el modelo_id existe
    $existeModelo = Modelo::find($data['modelo_id']);
    echo "   - Modelo ID válido: " . ($existeModelo ? 'SÍ' : 'NO') . "\n";

    if (!$existeModelo) {
        echo "   - Modelos disponibles:\n";
        $todosModelos = Modelo::with('marca')->get();
        foreach ($todosModelos as $m) {
            echo "     * ID: " . $m->id . ", " . $m->marca->nombre . " " . $m->nombre . "\n";
        }
    }
}
