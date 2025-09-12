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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id('id_empleado');
            $table->string('nombre');
            $table->string('apellido');
            $table->string('email')->unique();
            $table->string('telefono');
            $table->string('documento')->unique();
            $table->date('fecha_nacimiento')->nullable();
            $table->date('fecha_ingreso');
            $table->enum('estado', ['activo', 'inactivo', 'vacaciones', 'licencia'])->default('activo');
            $table->text('skills')->nullable(); // JSON con habilidades
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['estado', 'fecha_ingreso']);
            $table->index('documento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleados');
    }
};
