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
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('email')->nullable();
            $table->string('telefono');
            $table->text('direccion');
            $table->enum('tipo_cliente', ['residencial', 'comercial', 'empresa', 'hospital', 'critico'])->default('residencial');
            $table->boolean('es_premium')->default(false);
            $table->string('documento')->nullable();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index(['es_premium', 'activo']);
            $table->index('tipo_cliente');
            $table->index('telefono');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};
