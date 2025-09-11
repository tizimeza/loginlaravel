<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\VehiculoController; // <-- 1. IMPORTAR EL CONTROLADOR
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\GrupoTrabajoController;
use App\Http\Controllers\StockController;

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
    // Rutas para Tareas
    Route::get('/tareas', [TareaController::class, 'index'])->name('tareas.index');
    Route::post('/tareas', [TareaController::class, 'store'])->name('tareas.store');
    Route::get('/tareas/{id}/edit', [TareaController::class, 'edit'])->name('tareas.edit');
    Route::put('/tareas/{id}', [TareaController::class, 'update'])->name('tareas.update');
    Route::delete('/tareas/{id}', [TareaController::class, 'destroy'])->name('tareas.destroy');

    // <-- 2. AÑADIR LA RUTA DE RECURSO PARA VEHÍCULOS AQUÍ
    Route::resource('vehiculos', VehiculoController::class);
    
    // Rutas para Órdenes de Trabajo
    Route::resource('ordenes_trabajo', OrdenTrabajoController::class);
    Route::patch('ordenes_trabajo/{ordenTrabajo}/cambiar_estado', [OrdenTrabajoController::class, 'cambiarEstado'])->name('ordenes_trabajo.cambiar_estado');
    
    // Rutas para Grupos de Trabajo
    Route::resource('grupos_trabajo', GrupoTrabajoController::class);
    Route::patch('grupos_trabajo/{grupoTrabajo}/toggle_activo', [GrupoTrabajoController::class, 'toggleActivo'])->name('grupos_trabajo.toggle_activo');
    Route::post('grupos_trabajo/{grupoTrabajo}/agregar_miembro', [GrupoTrabajoController::class, 'agregarMiembro'])->name('grupos_trabajo.agregar_miembro');
    Route::delete('grupos_trabajo/{grupoTrabajo}/remover_miembro/{user}', [GrupoTrabajoController::class, 'removerMiembro'])->name('grupos_trabajo.remover_miembro');
    
    // Rutas para Stock
    Route::resource('stock', StockController::class);
    Route::patch('stock/{stock}/ajustar_stock', [StockController::class, 'ajustarStock'])->name('stock.ajustar_stock');
    Route::patch('stock/{stock}/toggle_activo', [StockController::class, 'toggleActivo'])->name('stock.toggle_activo');
    Route::get('stock-bajo', [StockController::class, 'stockBajo'])->name('stock.stock_bajo');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
