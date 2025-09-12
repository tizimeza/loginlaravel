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
            $table->foreignId('solicitud_id')->nullable()->constrained('solicitudes', 'id_solicitud')->onDelete('cascade')->after('numero_orden');
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
            $table->dropForeign(['solicitud_id']);
            $table->dropColumn('solicitud_id');
        });
    }
};
