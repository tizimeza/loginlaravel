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
            // Renombrar la columna skill a especialidad
            $table->renameColumn('skill', 'especialidad');
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
            // Revertir el renombre
            $table->renameColumn('especialidad', 'skill');
        });
    }
};
