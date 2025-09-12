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
        Schema::table('vehiculos', function (Blueprint $table) {
            // Agregar campos específicos para furgonetas de TecnoServi
            $table->enum('tipo_vehiculo', ['transit', 'kangoo', 'partner'])->default('transit')->after('patente');
            $table->string('marca')->default('Ford')->after('tipo_vehiculo');
            $table->string('modelo')->after('marca');
            $table->integer('capacidad_carga')->default(1000)->after('modelo'); // kg
            $table->string('combustible')->default('Diesel')->after('capacidad_carga');
            $table->date('fecha_vencimiento_vtv')->nullable()->after('combustible');
            $table->date('fecha_cambio_neumaticos')->nullable()->after('fecha_vencimiento_vtv');
            $table->text('servicios_pendientes')->nullable()->after('fecha_cambio_neumaticos');
            $table->enum('estado', ['disponible', 'en_uso', 'mantenimiento', 'fuera_servicio'])->default('disponible')->after('servicios_pendientes');
            $table->integer('kilometraje')->default(0)->after('estado');
            $table->text('observaciones')->nullable()->after('kilometraje');
            
            // Índices
            $table->index(['estado', 'tipo_vehiculo']);
            $table->index('fecha_vencimiento_vtv');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vehiculos', function (Blueprint $table) {
            // Remover campos agregados
            $table->dropColumn([
                'tipo_vehiculo', 'marca', 'modelo', 'capacidad_carga', 'combustible',
                'fecha_vencimiento_vtv', 'fecha_cambio_neumaticos', 'servicios_pendientes',
                'estado', 'kilometraje', 'observaciones'
            ]);
        });
    }
};
