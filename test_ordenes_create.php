<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Vehiculo;
use App\Models\Cliente;
use App\Models\GrupoTrabajo;
use App\Models\User;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE CARGA DE DATOS PARA ÓRDENES DE TRABAJO ===\n\n";

try {
    // Probar las mismas consultas que se hacen en el controlador
    echo "📋 Probando carga de clientes...\n";
    $clientes = Cliente::activos()->get();
    echo "✅ Clientes cargados: {$clientes->count()}\n";

    echo "📋 Probando carga de grupos de trabajo...\n";
    $gruposTrabajo = GrupoTrabajo::where('activo', true)->get();
    echo "✅ Grupos de trabajo cargados: {$gruposTrabajo->count()}\n";

    echo "📋 Probando carga de técnicos...\n";
    $tecnicos = User::all();
    echo "✅ Técnicos cargados: {$tecnicos->count()}\n";

    echo "📋 Probando carga de vehículos con relaciones...\n";
    $vehiculos = Vehiculo::with('modelo.marca')->get();
    echo "✅ Vehículos cargados: {$vehiculos->count()}\n";

    // Probar acceso a las propiedades de los vehículos
    echo "\n🔍 Probando acceso a propiedades de vehículos:\n";
    foreach ($vehiculos->take(3) as $vehiculo) {
        echo "  - {$vehiculo->patente}: ";
        try {
            if ($vehiculo->modelo && $vehiculo->modelo->marca) {
                echo "{$vehiculo->modelo->marca->nombre} {$vehiculo->modelo->nombre}\n";
            } else {
                echo "Sin modelo válido\n";
            }
        } catch (Exception $e) {
            echo "ERROR: {$e->getMessage()}\n";
        }
    }

    echo "\n✅ ¡Todas las consultas funcionan correctamente!\n";
    echo "La página de creación de órdenes de trabajo debería funcionar sin errores.\n";

} catch (Exception $e) {
    echo "❌ ERROR: {$e->getMessage()}\n";
    echo "📍 Archivo: {$e->getFile()}\n";
    echo "📍 Línea: {$e->getLine()}\n";
}
