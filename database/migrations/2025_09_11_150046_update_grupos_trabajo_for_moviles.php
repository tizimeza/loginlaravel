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
        Schema::table('grupos_trabajo', function (Blueprint $table) {
            // Agregar solo los campos nuevos necesarios
            $table->foreignId('vehiculo_id')->nullable()->constrained('vehiculos')->onDelete('set null')->after('lider_id');
            $table->integer('capacidad_maxima')->default(3)->after('activo'); // 2-3 empleados por móvil
            $table->string('zona_trabajo')->nullable()->after('capacidad_maxima');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('grupos_trabajo', function (Blueprint $table) {
            // Revertir cambios
            $table->dropForeign(['vehiculo_id']);
            $table->dropColumn(['vehiculo_id', 'capacidad_maxima', 'zona_trabajo']);
        });
    }
};
