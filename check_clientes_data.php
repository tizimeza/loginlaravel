<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Cliente;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== VERIFICACIÓN DE DATOS EN TABLA CLIENTES ===\n\n";

try {
    $clientes = Cliente::all();

    echo "📊 Total de clientes encontrados: " . $clientes->count() . "\n\n";

    if ($clientes->count() > 0) {
        echo "👥 LISTADO DE CLIENTES:\n";
        echo str_repeat("-", 80) . "\n";
        printf("%-3s %-25s %-15s %-12s %-8s\n", "ID", "Nombre", "Teléfono", "Tipo", "Activo");
        echo str_repeat("-", 80) . "\n";

        foreach ($clientes as $cliente) {
            $activo = $cliente->activo ? 'Sí' : 'No';
            $tipo = substr($cliente->tipo_cliente_formateado, 0, 10);

            printf("%-3d %-25s %-15s %-12s %-8s\n",
                $cliente->id,
                substr($cliente->nombre, 0, 24),
                substr($cliente->telefono, 0, 14),
                $tipo,
                $activo
            );
        }
        echo str_repeat("-", 80) . "\n";
    } else {
        echo "❌ No hay clientes registrados en la base de datos.\n";
        echo "🔧 Creando algunos clientes de ejemplo...\n\n";

        // Crear clientes de ejemplo
        $clientesEjemplo = [
            [
                'nombre' => 'Juan Pérez',
                'email' => 'juan.perez@email.com',
                'telefono' => '+54 11 1234-5678',
                'direccion' => 'Av. Corrientes 1234, CABA',
                'tipo_cliente' => 'residencial',
                'es_premium' => false,
                'documento' => 'DNI 12345678',
                'observaciones' => 'Cliente frecuente',
                'activo' => true
            ],
            [
                'nombre' => 'María González',
                'email' => 'maria.gonzalez@email.com',
                'telefono' => '+54 11 2345-6789',
                'direccion' => 'Calle Florida 567, CABA',
                'tipo_cliente' => 'comercial',
                'es_premium' => true,
                'documento' => 'CUIT 20-12345678-9',
                'observaciones' => 'Cliente premium - atención prioritaria',
                'activo' => true
            ],
            [
                'nombre' => 'Carlos Rodríguez',
                'email' => 'carlos.rodriguez@email.com',
                'telefono' => '+54 11 3456-7890',
                'direccion' => 'Hospital Italiano, Buenos Aires',
                'tipo_cliente' => 'hospital',
                'es_premium' => true,
                'documento' => 'CUIT 30-98765432-1',
                'observaciones' => 'Cliente institucional - soporte 24/7',
                'activo' => true
            ]
        ];

        foreach ($clientesEjemplo as $clienteData) {
            Cliente::create($clienteData);
            echo "✅ Cliente '{$clienteData['nombre']}' creado exitosamente.\n";
        }

        echo "\n🎉 ¡Clientes de ejemplo creados!\n";
        echo "Ahora puedes acceder a http://127.0.0.1:8000/clientes\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "📍 Archivo: " . $e->getFile() . " (línea " . $e->getLine() . ")\n";
}
