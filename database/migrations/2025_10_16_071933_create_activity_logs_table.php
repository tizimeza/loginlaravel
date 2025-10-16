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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Información del modelo afectado (polimórfica)
            $table->string('subject_type')->nullable(); // Tipo de modelo (Cliente, OrdenTrabajo, etc)
            $table->unsignedBigInteger('subject_id')->nullable(); // ID del registro

            // Usuario que realizó la acción
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            // Tipo de acción
            $table->string('event'); // created, updated, deleted, restored

            // Descripción de la acción
            $table->text('description')->nullable();

            // Datos del cambio (JSON)
            $table->json('old_values')->nullable(); // Valores anteriores
            $table->json('new_values')->nullable(); // Valores nuevos

            // Información adicional
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();

            $table->timestamps();

            // Índices para mejorar consultas
            $table->index(['subject_type', 'subject_id']);
            $table->index('user_id');
            $table->index('event');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
};
