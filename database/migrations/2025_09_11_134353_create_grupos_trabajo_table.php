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
        Schema::create('grupos_trabajo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('descripcion')->nullable();
            $table->foreignId('lider_id')->constrained('users')->onDelete('cascade');
            $table->boolean('activo')->default(true);
            $table->string('color')->default('primary');
            $table->enum('especialidad', [
                'mecanica_general', 'electricidad', 'carroceria', 'neumaticos', 
                'aire_acondicionado', 'frenos', 'transmision', 'motor', 
                'suspension', 'diagnostico'
            ])->default('mecanica_general');
            $table->timestamps();
            
            // Índices
            $table->index(['activo', 'especialidad']);
            $table->index('lider_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('grupos_trabajo');
    }
};
