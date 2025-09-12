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
        Schema::create('movil_empleado', function (Blueprint $table) {
            $table->id();
            $table->foreignId('movil_id')->constrained('grupos_trabajo', 'id')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados', 'id_empleado')->onDelete('cascade');
            $table->timestamps();
            
            // Índices únicos para evitar duplicados
            $table->unique(['movil_id', 'empleado_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movil_empleado');
    }
};
