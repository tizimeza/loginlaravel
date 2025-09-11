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
        Schema::table('tareas', function (Blueprint $table) {
            $table->foreignId('orden_trabajo_id')->nullable()->constrained('ordenes_trabajo')->onDelete('cascade');
            $table->index('orden_trabajo_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropForeign(['orden_trabajo_id']);
            $table->dropIndex(['orden_trabajo_id']);
            $table->dropColumn('orden_trabajo_id');
        });
    }
};
