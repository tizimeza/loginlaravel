#!/usr/bin/env php
<?php

/**
 * Script de Verificación de Requisitos
 * TecnoServi - Sistema de Gestión
 *
 * Este script verifica que tu sistema tenga todos los requisitos
 * necesarios para ejecutar la aplicación Laravel.
 */

echo "========================================\n";
echo "  TecnoServi - Verificación de Requisitos\n";
echo "========================================\n\n";

$errors = [];
$warnings = [];
$success = [];

// 1. Verificar versión de PHP
echo "[1/10] Verificando versión de PHP...\n";
$phpVersion = phpversion();
if (version_compare($phpVersion, '8.0.2', '>=')) {
    $success[] = "✓ PHP {$phpVersion} (Requerido: >= 8.0.2)";
} else {
    $errors[] = "✗ PHP {$phpVersion} - Se requiere PHP >= 8.0.2";
}

// 2. Verificar extensiones de PHP
echo "[2/10] Verificando extensiones de PHP...\n";
$requiredExtensions = [
    'openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json',
    'bcmath', 'fileinfo', 'pdo_mysql'
];

foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        $success[] = "✓ Extensión {$ext}";
    } else {
        $errors[] = "✗ Extensión {$ext} no está instalada";
    }
}

// 3. Verificar Composer
echo "[3/10] Verificando Composer...\n";
exec('composer --version 2>&1', $output, $return);
if ($return === 0) {
    $success[] = "✓ Composer está instalado";
} else {
    $errors[] = "✗ Composer no está instalado o no está en el PATH";
}

// 4. Verificar si existe el archivo .env
echo "[4/10] Verificando archivo .env...\n";
if (file_exists(__DIR__ . '/.env')) {
    $success[] = "✓ Archivo .env existe";
} else {
    $warnings[] = "⚠ Archivo .env no existe - Ejecuta: cp .env.example .env";
}

// 5. Verificar si existe vendor/
echo "[5/10] Verificando dependencias de Composer...\n";
if (is_dir(__DIR__ . '/vendor')) {
    $success[] = "✓ Carpeta vendor/ existe";
} else {
    $warnings[] = "⚠ Carpeta vendor/ no existe - Ejecuta: composer install";
}

// 6. Verificar permisos de escritura
echo "[6/10] Verificando permisos de escritura...\n";
$writeableDirs = ['storage', 'bootstrap/cache'];
foreach ($writeableDirs as $dir) {
    if (is_writable(__DIR__ . '/' . $dir)) {
        $success[] = "✓ {$dir}/ es escribible";
    } else {
        $errors[] = "✗ {$dir}/ no es escribible";
    }
}

// 7. Verificar configuración de memoria
echo "[7/10] Verificando límite de memoria PHP...\n";
$memoryLimit = ini_get('memory_limit');
$success[] = "✓ Límite de memoria: {$memoryLimit}";

// 8. Verificar max_execution_time
echo "[8/10] Verificando tiempo máximo de ejecución...\n";
$maxExecutionTime = ini_get('max_execution_time');
$success[] = "✓ Tiempo máximo de ejecución: {$maxExecutionTime}s";

// 9. Verificar post_max_size y upload_max_filesize
echo "[9/10] Verificando límites de subida de archivos...\n";
$postMaxSize = ini_get('post_max_size');
$uploadMaxFilesize = ini_get('upload_max_filesize');
$success[] = "✓ post_max_size: {$postMaxSize}";
$success[] = "✓ upload_max_filesize: {$uploadMaxFilesize}";

// 10. Verificar APP_KEY
echo "[10/10] Verificando APP_KEY...\n";
if (file_exists(__DIR__ . '/.env')) {
    $envContent = file_get_contents(__DIR__ . '/.env');
    if (preg_match('/APP_KEY=base64:.+/', $envContent)) {
        $success[] = "✓ APP_KEY está configurada";
    } else {
        $warnings[] = "⚠ APP_KEY no está configurada - Ejecuta: php artisan key:generate";
    }
}

// Mostrar resultados
echo "\n========================================\n";
echo "  RESULTADOS\n";
echo "========================================\n\n";

if (!empty($success)) {
    echo "✓ EXITOSOS:\n";
    foreach ($success as $msg) {
        echo "  {$msg}\n";
    }
    echo "\n";
}

if (!empty($warnings)) {
    echo "⚠ ADVERTENCIAS:\n";
    foreach ($warnings as $msg) {
        echo "  {$msg}\n";
    }
    echo "\n";
}

if (!empty($errors)) {
    echo "✗ ERRORES:\n";
    foreach ($errors as $msg) {
        echo "  {$msg}\n";
    }
    echo "\n";
    echo "Por favor, corrige los errores antes de continuar.\n";
    exit(1);
}

if (empty($warnings)) {
    echo "========================================\n";
    echo "  ✓ TODOS LOS REQUISITOS ESTÁN CUMPLIDOS\n";
    echo "========================================\n\n";
    echo "Puedes continuar con la instalación:\n";
    echo "  1. Configura tu base de datos en .env\n";
    echo "  2. Ejecuta: php artisan migrate\n";
    echo "  3. Ejecuta: php artisan db:seed\n";
    echo "  4. Ejecuta: php artisan serve\n\n";
} else {
    echo "========================================\n";
    echo "  ⚠ REQUISITOS CUMPLIDOS CON ADVERTENCIAS\n";
    echo "========================================\n\n";
    echo "Revisa las advertencias antes de continuar.\n\n";
}

exit(0);
