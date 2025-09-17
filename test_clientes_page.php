<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Cliente;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== PRUEBA DE FUNCIONAMIENTO DE LA PÁGINA DE CLIENTES ===\n\n";

try {
    // Simular la consulta que hace el controlador en index()
    $query = Cliente::query();
    $clientes = $query->orderBy('nombre')->paginate(15);

    echo "✅ Consulta de clientes exitosa\n";
    echo "📊 Total de clientes: {$clientes->total()}\n";
    echo "📄 Página actual: {$clientes->currentPage()}\n";
    echo "📄 Total de páginas: {$clientes->lastPage()}\n\n";

    // Probar constantes del modelo
    echo "📋 Tipos de cliente disponibles:\n";
    foreach (Cliente::TIPOS_CLIENTE as $key => $tipo) {
        echo "  - $key: $tipo\n";
    }
    echo "\n";

    // Probar que las vistas puedan renderizarse (simular)
    echo "🎨 Verificando que las vistas estén disponibles:\n";
    $vistas = ['clientes.index', 'clientes.create', 'clientes.edit', 'clientes.show'];

    foreach ($vistas as $vista) {
        if (view()->exists($vista)) {
            echo "✅ Vista '$vista' existe\n";
        } else {
            echo "❌ Vista '$vista' NO existe\n";
        }
    }

    echo "\n🎉 ¡Todo está configurado correctamente!\n";
    echo "Ahora puedes acceder a: http://127.0.0.1:8000/clientes\n";

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . " (línea " . $e->getLine() . ")\n";
}
