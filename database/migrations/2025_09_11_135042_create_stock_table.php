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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->enum('categoria', [
                'repuestos', 'aceites', 'filtros', 'neumaticos', 'baterias', 
                'frenos', 'suspension', 'electricidad', 'carroceria', 
                'herramientas', 'consumibles', 'otros'
            ])->default('otros');
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->integer('cantidad_actual')->default(0);
            $table->integer('cantidad_minima')->default(1);
            $table->integer('cantidad_maxima')->nullable();
            $table->decimal('precio_compra', 10, 2)->default(0);
            $table->decimal('precio_venta', 10, 2)->default(0);
            $table->string('ubicacion')->nullable();
            $table->string('proveedor')->nullable();
            $table->boolean('activo')->default(true);
            $table->string('imagen')->nullable();
            $table->timestamps();
            
            // Índices para mejorar rendimiento
            $table->index(['activo', 'categoria']);
            $table->index(['cantidad_actual', 'cantidad_minima']);
            $table->index('codigo');
            $table->index('nombre');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock');
    }
};
