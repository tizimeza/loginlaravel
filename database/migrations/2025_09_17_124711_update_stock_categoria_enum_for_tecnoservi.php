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
        // Actualizar el enum de la columna categoria para incluir categorías de TecnoServi
        DB::statement("ALTER TABLE stock MODIFY COLUMN categoria ENUM(
            'repuestos', 'aceites', 'filtros', 'neumaticos', 'baterias',
            'frenos', 'suspension', 'electricidad', 'carroceria',
            'herramientas', 'consumibles', 'otros',
            'routers', 'modems', 'cables', 'conectores', 'precintos',
            'grampas', 'cajas', 'antenas'
        ) DEFAULT 'otros'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Revertir a las categorías originales
        // Primero, actualizar cualquier categoría nueva a 'otros'
        DB::table('stock')
            ->whereIn('categoria', ['routers', 'modems', 'cables', 'conectores', 'precintos', 'grampas', 'cajas', 'antenas'])
            ->update(['categoria' => 'otros']);

        // Luego modificar el enum para quitar las categorías nuevas
        DB::statement("ALTER TABLE stock MODIFY COLUMN categoria ENUM(
            'repuestos', 'aceites', 'filtros', 'neumaticos', 'baterias',
            'frenos', 'suspension', 'electricidad', 'carroceria',
            'herramientas', 'consumibles', 'otros'
        ) DEFAULT 'otros'");
    }
};
