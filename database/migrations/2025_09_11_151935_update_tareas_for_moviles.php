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
        Schema::table('tareas', function (Blueprint $table) {
            $table->string('tipo')->nullable()->after('nombre');
            $table->enum('estado', ['pendiente', 'asignada', 'en_proceso', 'completada', 'cancelada'])->default('pendiente')->after('tipo');
            $table->foreignId('empleado_id')->nullable()->constrained('empleados', 'id_empleado')->onDelete('set null')->after('user_id');
            $table->foreignId('movil_id')->nullable()->constrained('grupos_trabajo', 'id')->onDelete('set null')->after('empleado_id');
            
            // Índices
            $table->index(['estado', 'tipo']);
            $table->index('movil_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['empleado_id', 'movil_id']);
            $table->dropColumn(['tipo', 'estado', 'empleado_id', 'movil_id']);
        });
    }
};
