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
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            // Agregar solo los campos nuevos necesarios para TecnoServi
            $table->enum('tipo_servicio', ['instalacion', 'reconexion', 'service', 'desconexion', 'mantenimiento'])->default('instalacion')->after('numero_orden');
            $table->text('motivo_no_terminada')->nullable()->after('observaciones');
            $table->boolean('es_cliente_premium')->default(false)->after('motivo_no_terminada');
            $table->string('coordenadas_gps')->nullable()->after('es_cliente_premium');
            $table->string('telefono_contacto')->nullable()->after('coordenadas_gps');
            $table->string('horario_preferido')->nullable()->after('telefono_contacto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ordenes_trabajo', function (Blueprint $table) {
            // Remover campos agregados
            $table->dropColumn([
                'tipo_servicio', 'motivo_no_terminada', 'es_cliente_premium',
                'coordenadas_gps', 'telefono_contacto', 'horario_preferido'
            ]);
        });
    }
};
