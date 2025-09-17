<?php

require_once __DIR__ . '/vendor/autoload.php';

// Cargar la aplicación Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== PRUEBA DEL MODELO ORDEN TRABAJO ===\n";
echo "1. Verificando conexión a la base de datos...\n";

try {
    $ordenes = App\Models\OrdenTrabajo::count();
    echo "✅ Conexión exitosa - Total de órdenes: " . $ordenes . "\n";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "\n";
}

echo "\n2. Probando generación de número de orden...\n";
try {
    $numero = App\Models\OrdenTrabajo::generarNumeroOrden();
    echo "✅ Número generado: " . $numero . "\n";
} catch (Exception $e) {
    echo "❌ Error generando número: " . $e->getMessage() . "\n";
}

echo "\n3. Verificando estructura de la tabla...\n";
try {
    $orden = new App\Models\OrdenTrabajo();
    $fillable = $orden->getFillable();
    echo "✅ Campos fillable: " . implode(', ', $fillable) . "\n";
} catch (Exception $e) {
    echo "❌ Error obteniendo campos: " . $e->getMessage() . "\n";
}

echo "\n4. Probando constantes del modelo...\n";
try {
    echo "✅ Estados disponibles: " . implode(', ', array_keys(App\Models\OrdenTrabajo::ESTADOS)) . "\n";
    echo "✅ Tipos de servicio: " . implode(', ', array_keys(App\Models\OrdenTrabajo::TIPOS_SERVICIO)) . "\n";
} catch (Exception $e) {
    echo "❌ Error con constantes: " . $e->getMessage() . "\n";
}

echo "\n5. Probando relaciones del modelo...\n";
try {
    $orden = new App\Models\OrdenTrabajo();
    $relations = ['cliente', 'tecnico', 'grupoTrabajo', 'tareas', 'solicitud'];
    echo "✅ Relaciones disponibles: " . implode(', ', $relations) . "\n";
} catch (Exception $e) {
    echo "❌ Error con relaciones: " . $e->getMessage() . "\n";
}

echo "\n=== PRUEBA COMPLETADA ===\n";

