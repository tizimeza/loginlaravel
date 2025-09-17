<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\DB;

// Crear la aplicación Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();

echo "=== ESTRUCTURA DE LA TABLA VEHICULOS ===\n\n";

try {
    $columns = DB::select("DESCRIBE vehiculos");

    foreach ($columns as $column) {
        echo "📋 " . $column->Field . " - " . $column->Type;
        if ($column->Null == 'NO') {
            echo " (NOT NULL)";
        }
        if ($column->Default !== null) {
            echo " DEFAULT '" . $column->Default . "'";
        }
        if ($column->Key) {
            echo " [" . $column->Key . "]";
        }
        echo "\n";
    }

} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
