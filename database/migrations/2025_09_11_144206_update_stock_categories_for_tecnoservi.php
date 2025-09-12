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
        // Las categorías ya están actualizadas en el modelo Stock
        // Esta migración se mantiene para historial pero no hace cambios
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock', function (Blueprint $table) {
            // Revertir a categorías originales
            $table->dropColumn('categoria');
            $table->enum('categoria', [
                'repuestos', 'aceites', 'filtros', 'neumaticos', 'baterias', 
                'frenos', 'suspension', 'electricidad', 'carroceria', 
                'herramientas', 'consumibles', 'otros'
            ])->default('otros')->after('descripcion');
        });
    }
};
