<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\VehiculoController; // <-- 1. IMPORTAR EL CONTROLADOR

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
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
