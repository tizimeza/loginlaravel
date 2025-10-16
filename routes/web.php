<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\VehiculoController; // <-- 1. IMPORTAR EL CONTROLADOR
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\GrupoTrabajoController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Rutas que requieren que el usuario esté autenticado
Route::middleware(['auth'])->group(function () {
    // Ruta de prueba para debugging
    Route::get('/test-tareas', function () {
        return view('test_laravel_simple');
    })->name('test.tareas');

    Route::get('/create-nuevo', function () {
        return view('ordenes_trabajo.create_nuevo');
    })->name('ordenes_trabajo.create_nuevo');

    // Rutas para Tareas (plantillas y asignación)
    Route::resource('tareas', TareaController::class)->parameters(['tareas' => 'tarea']);

    // Rutas adicionales para gestión de tareas
    Route::post('tareas/{tarea}/asignar-orden', [TareaController::class, 'asignarAOrden'])->name('tareas.asignar_orden');
    Route::get('ordenes_trabajo/{ordenTrabajo}/tareas', [TareaController::class, 'tareasDeOrden'])->name('tareas.orden');
    Route::patch('tareas/{tarea}/actualizar-estado', [TareaController::class, 'actualizarEstado'])->name('tareas.actualizar_estado');
    Route::get('tareas-disponibles', [TareaController::class, 'disponiblesParaOrden'])->name('tareas.disponibles');

    // <-- 2. AÑADIR LA RUTA DE RECURSO PARA VEHÍCULOS AQUÍ
    Route::resource('vehiculos', VehiculoController::class)->parameters(['vehiculos' => 'vehiculo']);
    
    // Rutas para Órdenes de Trabajo
    Route::resource('ordenes_trabajo', OrdenTrabajoController::class)->parameters(['ordenes_trabajo' => 'ordenTrabajo']);
    Route::patch('ordenes_trabajo/{ordenTrabajo}/cambiar_estado', [OrdenTrabajoController::class, 'cambiarEstado'])->name('ordenes_trabajo.cambiar_estado');
    
    // Rutas para Grupos de Trabajo
    Route::resource('grupos_trabajo', GrupoTrabajoController::class)->parameters(['grupos_trabajo' => 'grupoTrabajo']);
    Route::patch('grupos_trabajo/{grupoTrabajo}/toggle_activo', [GrupoTrabajoController::class, 'toggleActivo'])->name('grupos_trabajo.toggle_activo');
    Route::post('grupos_trabajo/{grupoTrabajo}/agregar_miembro', [GrupoTrabajoController::class, 'agregarMiembro'])->name('grupos_trabajo.agregar_miembro');
    Route::delete('grupos_trabajo/{grupoTrabajo}/remover_miembro/{user}', [GrupoTrabajoController::class, 'removerMiembro'])->name('grupos_trabajo.remover_miembro');
    
    // Rutas para Stock
    Route::resource('stock', StockController::class)->parameters(['stock' => 'stock']);
    Route::patch('stock/{stock}/ajustar_stock', [StockController::class, 'ajustarStock'])->name('stock.ajustar_stock');
    Route::patch('stock/{stock}/toggle_activo', [StockController::class, 'toggleActivo'])->name('stock.toggle_activo');
    Route::get('stock-bajo', [StockController::class, 'stockBajo'])->name('stock.stock_bajo');
    
    // Rutas para Clientes
    Route::resource('clientes', ClienteController::class)->parameters(['clientes' => 'cliente']);
    Route::patch('clientes/{cliente}/toggle_activo', [ClienteController::class, 'toggleActivo'])->name('clientes.toggle_activo');

    // Rutas para Usuarios
    Route::resource('users', UserController::class);

    // Rutas para Roles y Permisos
    Route::resource('roles', RoleController::class);

    // Rutas para Reportes PDF
    Route::prefix('reportes')->name('reportes.')->group(function () {
        // PDF de orden de trabajo individual
        Route::get('orden-trabajo/{id}/pdf', [ReporteController::class, 'ordenTrabajoPDF'])->name('orden-trabajo-pdf');

        // PDF de inventario completo
        Route::get('inventario/pdf', [ReporteController::class, 'inventarioPDF'])->name('inventario-pdf');

        // PDF de órdenes por periodo
        Route::get('ordenes-periodo/pdf', [ReporteController::class, 'ordenesPorPeriodoPDF'])->name('ordenes-periodo-pdf');

        // PDF de clientes
        Route::get('clientes/pdf', [ReporteController::class, 'clientesPDF'])->name('clientes-pdf');

        // PDF de vehículos
        Route::get('vehiculos/pdf', [ReporteController::class, 'vehiculosPDF'])->name('vehiculos-pdf');
    });
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
