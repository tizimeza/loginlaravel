<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Agregar columnas que faltan en tareas si no existen
        Schema::table('tareas', function (Blueprint $table) {
            if (!Schema::hasColumn('tareas', 'tipo')) {
                $table->string('tipo')->nullable()->after('nombre');
            }
            if (!Schema::hasColumn('tareas', 'estado')) {
                $table->enum('estado', ['pendiente', 'asignada', 'en_proceso', 'completada', 'cancelada'])->default('pendiente')->after('tipo');
            }
            if (!Schema::hasColumn('tareas', 'empleado_id')) {
                $table->foreignId('empleado_id')->nullable()->constrained('empleados', 'id')->onDelete('set null')->after('user_id');
            }
            if (!Schema::hasColumn('tareas', 'movil_id')) {
                $table->foreignId('movil_id')->nullable()->constrained('grupos_trabajo', 'id')->onDelete('set null')->after('empleado_id');
            }
        });

        // Crear tabla movil_empleado si no existe
        if (!Schema::hasTable('movil_empleado')) {
            Schema::create('movil_empleado', function (Blueprint $table) {
                $table->id();
                $table->foreignId('movil_id')->constrained('grupos_trabajo', 'id')->onDelete('cascade');
                $table->foreignId('empleado_id')->constrained('empleados', 'id')->onDelete('cascade');
                $table->timestamps();
                
                // Índices únicos para evitar duplicados
                $table->unique(['movil_id', 'empleado_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // No hacer nada en el rollback para evitar problemas
    }
};