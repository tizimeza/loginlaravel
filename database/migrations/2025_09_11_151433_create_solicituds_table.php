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
        Schema::create('solicitudes', function (Blueprint $table) {
            $table->id('id_solicitud');
            $table->foreignId('cliente_id')->constrained('clientes', 'id')->onDelete('cascade');
            $table->date('fecha');
            $table->enum('estado', ['pendiente', 'procesada', 'cancelada'])->default('pendiente');
            $table->text('descripcion')->nullable();
            $table->string('tipo_servicio')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
            
            // Índices
            $table->index(['estado', 'fecha']);
            $table->index('cliente_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitudes');
    }
};
