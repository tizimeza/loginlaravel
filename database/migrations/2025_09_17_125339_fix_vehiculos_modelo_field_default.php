<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Corregir el campo modelo para que tenga un valor por defecto
        DB::statement("ALTER TABLE vehiculos MODIFY COLUMN modelo varchar(255) DEFAULT 'Sin especificar'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir: quitar el valor por defecto del campo modelo
        DB::statement("ALTER TABLE vehiculos MODIFY COLUMN modelo varchar(255) NOT NULL");
    }
};
