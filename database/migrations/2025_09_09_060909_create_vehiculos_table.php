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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('patente')->unique();
            $table->string('color');
            $table->year('anio');

            // Esta es la clave foránea que conecta con la tabla 'modelos'.
            // constrained() le dice a Laravel que la tabla es 'modelos'.
            // onDelete('cascade') borrará los vehículos si se elimina su modelo.
            $table->foreignId('modelo_id')
                  ->constrained('modelos')
                  ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehiculos');
    }
};

