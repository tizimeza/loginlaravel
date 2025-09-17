<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use App\Models\Stock;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

// Datos de prueba para insertar un producto con categoria 'routers'
$data = [
    'codigo' => 'ROO1',
    'nombre' => 'Router WiFi TP-Link',
    'descripcion' => 'Router WiFi de alta velocidad',
    'categoria' => 'routers',
    'marca' => 'TP-Link',
    'modelo' => 'TL-WR840N',
    'cantidad_actual' => 50,
    'cantidad_minima' => 5,
    'cantidad_maxima' => 100,
    'precio_compra' => 20.00,
    'precio_venta' => 35.00,
    'ubicacion' => 'depósito A',
    'proveedor' => 'X',
    'activo' => true,
    'imagen' => 'stock/1758134478_images.jpg'
];

try {
    $stock = Stock::create($data);
    echo "✅ Producto insertado exitosamente!\n";
    echo "ID: " . $stock->id . "\n";
    echo "Código: " . $stock->codigo . "\n";
    echo "Nombre: " . $stock->nombre . "\n";
    echo "Categoría: " . $stock->categoria . "\n";
} catch (Exception $e) {
    echo "❌ Error al insertar el producto: " . $e->getMessage() . "\n";
}
