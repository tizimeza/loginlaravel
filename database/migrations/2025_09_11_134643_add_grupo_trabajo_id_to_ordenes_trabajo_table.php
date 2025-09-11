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
            $table->foreignId('grupo_trabajo_id')->nullable()->constrained('grupos_trabajo')->onDelete('set null');
            $table->index('grupo_trabajo_id');
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
            $table->dropForeign(['grupo_trabajo_id']);
            $table->dropIndex(['grupo_trabajo_id']);
            $table->dropColumn('grupo_trabajo_id');
        });
    }
};
